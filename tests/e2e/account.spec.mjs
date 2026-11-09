// The account's commands: Halt at once from both buttons, Resume only after the password and a fresh code.
import { test, expect } from '@playwright/test';
import { checkLayout, reauthenticate, watchConsole } from './support.mjs';

/** Picks the paper account and waits for the screen to show it: the first account by name may be another one. */
async function pickPaper(page) {
    await Promise.all([page.waitForResponse((r) => r.url().includes('/livewire') && r.ok() && (r.request().postData() ?? '').includes('"updates":{"account"')), page.getByLabel('Account').selectOption({ label: 'paper (paper)' })]);
}

test('halts at once and resumes after re-authentication', async ({ page }) => {
    const errors = watchConsole(page);
    await page.goto('/account');
    await pickPaper(page);

    await page.getByRole('button', { name: 'Halt account' }).click();
    await expect(page.getByRole('status').filter({ hasText: 'Halt queued for paper' })).toBeVisible();
    await expect(page.getByText(/command\(s\) waiting for the next minute/)).toBeVisible();

    await page.getByRole('button', { name: 'Halt', exact: true }).click();
    await expect(page.getByText(/Halt queued for .*paper/).first()).toBeVisible();

    // The session may still be confirmed from another test; otherwise Resume goes to the password and code first.
    const queued = page.getByRole('status').filter({ hasText: 'Resume queued for paper' });
    await page.getByRole('button', { name: 'Resume', exact: true }).first().click();
    await expect(queued.or(page.getByRole('heading', { name: 'Confirm it is you' }))).toBeVisible();

    if (page.url().endsWith('/confirm')) {
        await reauthenticate(page, '/account');
        await pickPaper(page);
        await page.getByRole('button', { name: 'Resume', exact: true }).first().click();
    }

    await expect(queued).toBeVisible();
    await checkLayout(page);
    expect(errors).toEqual([]);
});
