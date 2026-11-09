// The end-to-end suite (tests/e2e, docs/conventions.md): the real app on a scratch database, trading_e2e, with a
// fake Telegram on port 8124. `npm run e2e` resets the database, starts both servers and runs every screen at six
// widths. It never touches a live account, a real key or production data.
import { defineConfig } from '@playwright/test';
import { E2E_ENV, BASE_URL } from './tests/e2e/env.mjs';

const sizes = [
    ['phone-360', 360, 740],
    ['phone-390', 390, 844],
    ['tablet-768', 768, 1024],
    ['laptop-1024', 1024, 768],
    ['desktop-1280', 1280, 800],
    ['wide-1920', 1920, 1080],
];

export default defineConfig({
    testDir: './tests/e2e',
    outputDir: './storage/e2e/results',
    globalSetup: './tests/e2e/global-setup.mjs',
    fullyParallel: false,
    workers: 1,
    retries: 0,
    timeout: 120_000,
    expect: { timeout: 10_000 },
    reporter: [['list'], ['html', { open: 'never', outputFolder: './storage/e2e/report' }]],
    use: { baseURL: BASE_URL, trace: 'off', screenshot: 'only-on-failure' },
    projects: [
        { name: 'login', testMatch: /login\.setup\.mjs/, use: { viewport: { width: 1280, height: 800 } } },
        ...sizes.map(([name, width, height]) => ({
            name,
            testMatch: /\.spec\.mjs/,
            dependencies: ['login'],
            use: { viewport: { width, height }, storageState: './storage/e2e/operator.json', deviceScaleFactor: 1, hasTouch: width < 768 },
        })),
    ],
    webServer: [
        {
            // Laravel's router for PHP's server reads the public directory from the working directory.
            command: 'php -S 127.0.0.1:8123 ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php',
            cwd: './public',
            url: `${BASE_URL}/up`,
            env: E2E_ENV,
            reuseExistingServer: false,
            stdout: 'ignore',
            stderr: 'ignore',
        },
        { command: 'node tests/e2e/fake-telegram.mjs', url: 'http://127.0.0.1:8124/__received', reuseExistingServer: false },
    ],
});
