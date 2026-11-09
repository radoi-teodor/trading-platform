// Shared steps of the end-to-end suite: TOTP codes, re-authentication and the checks every screen must pass.
import { createHmac } from 'node:crypto';
import { existsSync, readFileSync, writeFileSync } from 'node:fs';
import { expect } from '@playwright/test';

export const EMAIL = 'e2e@local.test';
export const PASSWORD = 'e2e local password only';
const SECRET = 'JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP';
const USED = './storage/e2e/used-codes.json';

function totp(counter) {
    const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    const bits = [...SECRET].map((c) => alphabet.indexOf(c).toString(2).padStart(5, '0')).join('');
    const key = Buffer.from(bits.match(/.{8}/g).map((b) => parseInt(b, 2)));
    const message = Buffer.alloc(8);
    message.writeBigUInt64BE(BigInt(counter));
    const hash = createHmac('sha1', key).update(message).digest();
    const offset = hash[hash.length - 1] & 15;

    return String((hash.readUInt32BE(offset) & 0x7fffffff) % 1000000).padStart(6, '0');
}

/**
 * A code the app has not accepted yet: Fortify refuses a code twice and accepts the previous, current and next
 * 30-second windows, so the suite waits only when all three are used.
 */
export async function freshCode() {
    for (;;) {
        const used = existsSync(USED) ? JSON.parse(readFileSync(USED, 'utf8')) : [];
        const now = Math.floor(Date.now() / 30000);
        const counter = [now, now + 1, now - 1].find((c) => !used.includes(totp(c)) && !used.includes(c));

        if (counter !== undefined) {
            writeFileSync(USED, JSON.stringify([...used, counter, totp(counter)]));

            return totp(counter);
        }

        await new Promise((resolve) => setTimeout(resolve, 1000));
    }
}

/** Answers the password and code page, then waits to be back where the action started. */
export async function reauthenticate(page, back) {
    await expect(page).toHaveURL(/\/confirm$/);
    await page.getByLabel('Password').fill(PASSWORD);
    await page.getByLabel(/code/i).fill(await freshCode());
    await page.getByRole('button', { name: /confirm|continue/i }).click();
    await expect(page).toHaveURL(new RegExp(`${back}$`));
}

/** Collects console errors and failed page scripts; the test fails on any at its end. */
export function watchConsole(page) {
    const errors = [];
    page.on('console', (message) => { if (message.type() === 'error') errors.push(message.text()); });
    page.on('pageerror', (error) => errors.push(error.message));

    return errors;
}

/** The rules every screen keeps at every width (docs/brand.md, A6). */
export async function checkLayout(page) {
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
    expect(overflow, 'no horizontal scroll on the page').toBeLessThanOrEqual(0);

    // Every control is inside the viewport, unless it sits in a box that scrolls on purpose (a wide table).
    const outside = await page.evaluate(() => {
        const scrolls = (el) => {
            for (let a = el.parentElement; a; a = a.parentElement) {
                const style = getComputedStyle(a);
                if (['auto', 'scroll'].includes(style.overflowX) && a.scrollWidth > a.clientWidth) return true;
            }

            return false;
        };

        return [...document.querySelectorAll('button, input, select, a, [role="alert"], [role="status"]')]
            .filter((el) => el.getClientRects().length > 0 && getComputedStyle(el).visibility !== 'hidden')
            .filter((el) => { const r = el.getBoundingClientRect(); return (r.right > window.innerWidth + 1 || r.left < -1) && !scrolls(el); })
            .map((el) => `${el.tagName.toLowerCase()} "${(el.textContent || el.getAttribute('aria-label') || el.getAttribute('name') || '').trim().slice(0, 40)}"`);
    });
    expect(outside, 'controls outside the viewport').toEqual([]);

    await expect(page.getByRole('button', { name: 'Halt', exact: true })).toBeInViewport();
    await expect(page.getByText(/^(PAPER|TESTNET|LIVE|BACKTEST)/).first()).toBeVisible();

    for (const screen of ['Chart', 'Strategies', 'Account', 'Settings']) {
        await expect(page.getByRole('link', { name: screen, exact: true }).filter({ visible: true }).first()).toBeVisible();
    }
}
