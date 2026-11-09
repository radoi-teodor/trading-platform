// Logs the operator in once, with the password and a TOTP code, and keeps the session for every other test.
import { test, expect } from '@playwright/test';
import { EMAIL, PASSWORD, freshCode, watchConsole } from './support.mjs';

test('logs in with the password and a code, and refuses a wrong code', async ({ page }) => {
    const errors = watchConsole(page);
    await page.goto('/chart');
    await expect(page).toHaveURL(/\/login$/);

    await page.getByLabel('Email').fill(EMAIL);
    await page.getByLabel('Password').fill('not the password');
    await page.getByRole('button', { name: 'Log in' }).click();
    await expect(page).toHaveURL(/\/login$/);

    await page.getByLabel('Email').fill(EMAIL);
    await page.getByLabel('Password').fill(PASSWORD);
    await page.getByRole('button', { name: 'Log in' }).click();
    await expect(page).toHaveURL(/two-factor-challenge$/);

    await page.getByLabel(/code/i).fill('000000');
    await page.getByRole('button', { name: 'Continue' }).click();
    await expect(page).toHaveURL(/two-factor-challenge$/);

    await page.getByLabel(/code/i).fill(await freshCode());
    await page.getByRole('button', { name: 'Continue' }).click();
    await expect(page).toHaveURL(/\/chart$/);
    await expect(page.getByRole('button', { name: 'Halt', exact: true })).toBeVisible();

    await page.context().storageState({ path: './storage/e2e/operator.json' });
    expect(errors).toEqual([]);
});
