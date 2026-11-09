// The editor: errors with line and column while typing, the read-back, a new version and a backtest in the queue.
import { test, expect } from '@playwright/test';
import { checkLayout, reauthenticate, watchConsole } from './support.mjs';

const SEEDED = 'ema-cross-e2e@v1';

async function type(page, text) {
    const editor = page.locator('.cm-content');
    await editor.click();
    await page.keyboard.press('ControlOrMeta+a');
    await page.keyboard.press('Delete');
    await page.keyboard.insertText(text);
}

test('compiles while typing, saves a version and queues a backtest', async ({ page }, info) => {
    const errors = watchConsole(page);
    await page.goto('/strategies');
    await page.getByRole('cell', { name: SEEDED }).click();
    await expect(page.locator('.cm-content')).toContainText('strategy: ema-cross-e2e');
    const document = await page.locator('.cm-content').innerText();

    await type(page, document.replace('1.5 * atr(14)', 'close > 2%'));
    await expect(page.getByRole('alert')).toContainText(/line \d+, column \d+: .*cannot compare a price series with a percent/);
    await expect(page.getByRole('button', { name: 'Save as a version' })).toBeDisabled();

    // A new version per project: a different stop for each width.
    const stop = `${1 + info.project.name.length / 10} * atr(14)`;
    await type(page, document.replace('1.5 * atr(14)', stop));
    await expect(page.getByRole('alert')).toHaveCount(0);
    await expect(page.getByLabel('Read-back')).toContainText('On BTC/USDT spot, every time a 15-minute candle closes');
    await page.getByRole('button', { name: 'Save as a version' }).click();
    await expect(page.getByRole('status')).toContainText(/Saved as ema-cross-e2e@v\d+\.|Unchanged: it is ema-cross-e2e@v\d+\./);

    await page.getByPlaceholder('from YYYY-MM-DD').fill('2026-10-03');
    await page.getByPlaceholder('to YYYY-MM-DD').fill('2026-10-08');
    await page.getByRole('button', { name: 'Backtest' }).click();
    // No worker runs in this suite, so after two waiting runs the queue refuses a third (D-62).
    await expect(page.getByRole('status')).toContainText(/Backtest \d+ queued\.|operator already has 2 runs waiting or running; wait for one to finish\./);

    await checkLayout(page);
    expect(errors).toEqual([]);
});

test('promotes a qualified paper deployment to live micro after re-authentication', async ({ page }) => {
    const errors = watchConsole(page);
    await page.goto('/strategies');
    const ladder = page.getByLabel('Ladder');
    const paper = ladder.locator('div').filter({ hasText: `${SEEDED}` }).filter({ hasText: 'paper · paper · size 1' }).first();
    await expect(paper).toContainText(/40 days, 32 trades, in band/);
    await checkLayout(page);

    const promote = paper.getByRole('button', { name: 'Promote to live micro, a quarter of the size' });
    page.once('dialog', (dialog) => dialog.accept());
    await promote.click();
    const done = page.getByRole('status').filter({ hasText: /It goes live micro from \d{4}-\d\d-\d\d \d\d:\d\d UTC at 0\.25 of its size; restart the engine of live/ });
    await expect(done.or(page.getByRole('heading', { name: 'Confirm it is you' }))).toBeVisible();

    if (page.url().endsWith('/confirm')) {
        await reauthenticate(page, '/strategies');
        page.once('dialog', (dialog) => dialog.accept());
        await page.getByLabel('Ladder').getByRole('button', { name: 'Promote to live micro, a quarter of the size' }).first().click();
    }

    await expect(done).toBeVisible();
    await expect(page.getByLabel('Ladder')).toContainText(/live · live micro · size 0\.25\s*Takes effect/);
    await checkLayout(page);
    expect(errors).toEqual([]);
});
