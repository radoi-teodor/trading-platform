// Every screen at every width: no horizontal scroll, every control on screen, Halt, the mode and the navigation
// visible, no console error. A screenshot of each is kept in storage/e2e/screens.
import { test, expect } from '@playwright/test';
import { checkLayout, watchConsole } from './support.mjs';

for (const screen of ['chart', 'strategies', 'account', 'settings']) {
    test(`${screen} keeps its layout`, async ({ page }, info) => {
        const errors = watchConsole(page);
        await page.goto(`/${screen}`);
        await page.waitForLoadState('networkidle');

        if (screen === 'chart') {
            await expect(page.locator('canvas').first()).toBeVisible();
        }

        await checkLayout(page);
        await page.screenshot({ path: `./storage/e2e/screens/${screen}-${info.project.name}.png`, fullPage: true });
        expect(errors).toEqual([]);
    });
}
