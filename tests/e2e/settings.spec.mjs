// The Settings screen (D-75): Telegram, notifications, accounts, testnet keys and MCP tokens, every secret entered
// once and never shown back; risk read-only.
import { test, expect } from '@playwright/test';
import { TELEGRAM_URL } from './env.mjs';
import { checkLayout, reauthenticate, watchConsole } from './support.mjs';

async function confirmed(page) {
    await page.goto('/settings');
    const confirm = page.getByRole('button', { name: 'Confirm to change' }).first();
    await expect(confirm.or(page.getByLabel('Bot token'))).toBeVisible();

    if (await confirm.isVisible()) {
        await confirm.click();
        await reauthenticate(page, '/settings');
    }

    await expect(page.getByLabel('Bot token')).toBeVisible();
}

test('configures Telegram and the notifications', async ({ page }, info) => {
    const errors = watchConsole(page);
    const width = String(info.project.use.viewport.width);
    const token = `555000${width}:e2e-not-a-real-token-${width}`;
    const chat = `7${width}`;
    await confirmed(page);

    await page.getByLabel('Bot token').fill(token);
    await page.getByLabel('Chat id').fill(chat);
    await page.getByRole('button', { name: 'Save' }).click();
    await expect(page.getByRole('status').filter({ hasText: 'Telegram is set.' })).toBeVisible();
    await expect(page.locator('[data-test="token-source"]')).toHaveText('set in the app');
    await expect(page.locator('[data-test="chat-source"]')).toHaveText('set in the app');

    for (const shown of [await page.content(), await (await page.reload(), page).content()]) {
        expect(shown).not.toContain(token);
        expect(shown).not.toContain(chat);
    }

    await page.getByRole('button', { name: 'Send a test message' }).click();
    await expect(page.getByRole('status').filter({ hasText: 'Telegram accepted the test message.' })).toBeVisible();
    const received = await (await fetch(`${TELEGRAM_URL}/__received`)).json();
    expect(received.at(-1)).toMatchObject({ chatId: chat, text: 'Trading X: a test message from the Settings screen.' });

    const shock = page.locator('[data-test="notify-volatility_shock"]');
    const before = await shock.isChecked();
    await Promise.all([page.waitForResponse((r) => r.url().includes('/livewire') && r.ok()), shock.click()]);
    await expect(shock).toBeChecked({ checked: !before });
    await page.reload();
    await expect(page.locator('[data-test="notify-volatility_shock"]')).toBeChecked({ checked: !before });
    await expect(page.getByText('Always sent: Halts, reconciliation')).toBeVisible();

    await checkLayout(page);
    expect(errors).toEqual([]);
});

test('opens a testnet account and stores its key without showing it back', async ({ page }, info) => {
    const errors = watchConsole(page);
    const name = `net-${info.project.name}`;
    await confirmed(page);

    await page.getByLabel('Name', { exact: true }).first().fill(name);
    await page.getByLabel('Mode').selectOption('testnet');
    await page.getByLabel('Spot USDT').fill('1000');
    await page.getByRole('button', { name: 'Open account' }).click();
    await expect(page.getByRole('status').filter({ hasText: `${name} (testnet) is open` })).toBeVisible();

    await page.getByLabel('Mode').selectOption('paper');
    await page.getByLabel('Name', { exact: true }).first().fill('bad name');
    await page.getByRole('button', { name: 'Open account' }).click();
    await expect(page.getByRole('status').filter({ hasText: 'An account name is lowercase letters, digits and hyphens.' })).toBeVisible();

    const key = `e2ekey${'a'.repeat(10)}${info.project.name.replace(/\D/g, '').padStart(4, '0')}`;
    const secret = `e2esecret${'b'.repeat(16)}`;
    await page.getByRole('combobox', { name: /^Account/ }).selectOption({ label: name });
    await page.getByLabel('API key').fill(key);
    await page.getByLabel('API secret').fill(secret);
    await page.getByRole('button', { name: 'Store key' }).click();
    await expect(page.getByRole('status').filter({ hasText: `The key of ${name} is stored` })).toBeVisible();
    await expect(page.getByText(`ends in ${key.slice(-4)}`).first()).toBeVisible();

    const shown = await page.content();
    expect(shown).not.toContain(secret);
    expect(shown).not.toContain(key);

    await checkLayout(page);
    expect(errors).toEqual([]);
});

test('shows a new MCP token once and revokes it', async ({ page }, info) => {
    const errors = watchConsole(page);
    const name = `agent-${info.project.name}`;
    await confirmed(page);

    await page.getByLabel('Name', { exact: true }).last().fill(name);
    await page.getByRole('button', { name: 'Create token' }).click();
    const shown = page.locator('[data-test="new-token"]');
    await expect(shown).toHaveText(/^tx_[A-Za-z0-9]{40}$/);
    const token = await shown.innerText();
    await checkLayout(page);

    await page.reload();
    expect(await page.content()).not.toContain(token);

    const row = page.getByRole('listitem').filter({ hasText: name });
    await row.getByRole('button', { name: 'Revoke' }).click();
    await expect(page.getByRole('status').filter({ hasText: `Revoked the token of ${name}.` })).toBeVisible();
    await expect(page.getByRole('listitem').filter({ hasText: name })).toContainText('revoked');

    await expect(page.getByText('Changing a limit is a code change with a test (D-19).')).toBeVisible();
    await expect(page.getByText('risk_per_trade')).toBeVisible();
    expect(errors).toEqual([]);
});

test('opens a live account, approves it for live trading and withdraws the approval', async ({ page }, info) => {
    const errors = watchConsole(page);
    const name = `live-${info.project.name}`;
    await confirmed(page);

    await page.getByLabel('Name', { exact: true }).first().fill(name);
    await page.getByLabel('Mode').selectOption('live');
    await page.getByRole('button', { name: 'Open account' }).click();
    await expect(page.getByRole('status').filter({ hasText: `${name} (live) is open` })).toBeVisible();

    const box = page.getByLabel(`Live approval of ${name}`);
    await expect(box).toContainText('not approved for live trading');
    page.once('dialog', (dialog) => dialog.accept());
    await box.getByRole('button', { name: 'Approve live trading' }).click();
    await expect(page.getByRole('status').filter({ hasText: `${name} is approved for live trading; it takes effect at` })).toBeVisible();
    await expect(box).toContainText('takes effect');
    await checkLayout(page);

    await box.getByRole('button', { name: 'Withdraw' }).click();
    await expect(page.getByRole('status').filter({ hasText: `The live approval of ${name} is withdrawn` })).toBeVisible();
    await expect(box).toContainText('not approved for live trading');
    expect(errors).toEqual([]);
});

test('adds an instrument to the universe, queues its import and removes it', async ({ page }) => {
    const errors = watchConsole(page);
    await page.goto('/settings');

    await page.getByLabel('Add an instrument').fill('NOPE/USDT:USDT');
    await page.getByRole('button', { name: 'Add', exact: true }).click();
    await expect(page.getByRole('status').filter({ hasText: 'NOPE/USDT:USDT is not a stored instrument; run market:instruments first.' })).toBeVisible();

    await page.getByLabel('Add an instrument').fill('DOGE/USDT:USDT');
    await page.getByRole('button', { name: 'Add', exact: true }).click();
    await expect(page.getByRole('status').filter({ hasText: 'DOGE/USDT:USDT joins the universe' })).toBeVisible();
    await expect(page.getByLabel('Scalping data of DOGE/USDT:USDT')).not.toBeChecked();

    await page.getByLabel('Instrument to import').selectOption({ label: 'DOGE/USDT:USDT' });
    await page.getByPlaceholder('2025-11-01').fill('2026-11-01');
    await page.getByRole('button', { name: 'Import', exact: true }).last().click();
    await expect(page.getByRole('status').filter({ hasText: /Import of DOGE\/USDT:USDT from 2026-11-01 queued as job \d+: \d+ days, about [\d.]+ MB/ })).toBeVisible();
    await expect(page.getByLabel('Imports')).toContainText('DOGE/USDT:USDT from 2026-11-01');
    await checkLayout(page);

    const row = page.getByRole('row').filter({ hasText: 'DOGE/USDT:USDT' });
    page.once('dialog', (dialog) => dialog.accept());
    await row.getByRole('button', { name: 'Remove' }).click();
    await expect(page.getByRole('status').filter({ hasText: 'DOGE/USDT:USDT leaves the universe; its stored history stays.' })).toBeVisible();
    await expect(page.getByRole('row').filter({ hasText: 'DOGE/USDT:USDT' })).toHaveCount(0);
    expect(errors).toEqual([]);
});
