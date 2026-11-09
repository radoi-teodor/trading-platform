// The chart: layers from the library added and removed, timeframe, table view and an expression that cannot be drawn.
import { test, expect } from '@playwright/test';
import { checkLayout, watchConsole } from './support.mjs';

test('adds and removes layers, changes the timeframe and shows the table', async ({ page }) => {
    const errors = watchConsole(page);
    await page.goto('/chart');
    const layers = page.getByLabel('Layers');
    await expect(layers).toContainText('ema(9)a price series');
    await expect(layers).toContainText('ema(21)a price series');

    await page.getByPlaceholder('ema(21) on 4h').fill('rsi(14)');
    await page.getByRole('button', { name: 'Add layer' }).click();
    await expect(layers).toContainText('rsi(14)a value series');

    // A layer from the function list, with its required arguments filled in.
    await page.getByLabel('Function').selectOption('bb');
    await page.getByRole('button', { name: 'Add layer' }).click();
    await expect(layers).toContainText('bb().upper');

    await page.getByRole('button', { name: 'Remove ema(9)' }).click();
    await expect(layers).not.toContainText('ema(9)');

    await page.getByLabel('Timeframe').selectOption('15m');
    await expect(page.getByRole('alert')).toHaveCount(0);

    await page.getByLabel('Table view').check();
    await expect(page.getByRole('columnheader', { name: 'rsi(14)' })).toBeVisible();
    await expect(page.locator('tbody tr').first()).toContainText('2026-10');

    await page.getByPlaceholder('ema(21) on 4h').fill('ema(9');
    await page.getByRole('button', { name: 'Add layer' }).click();
    await expect(page.getByRole('alert')).toContainText('expected ) to close the call to ema');
    await page.getByRole('button', { name: 'Remove ema(9' }).click();
    await expect(page.getByRole('alert')).toHaveCount(0);

    await checkLayout(page);
    expect(errors).toEqual([]);
});
