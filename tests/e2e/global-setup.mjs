// Resets the scratch database and the file cache (used TOTP codes, throttles) before the suite.
import { execFileSync } from 'node:child_process';
import { mkdirSync, rmSync } from 'node:fs';
import { E2E_ENV } from './env.mjs';

export default function globalSetup() {
    const artisan = (...args) => execFileSync('php', ['artisan', ...args], { env: E2E_ENV, stdio: 'inherit' });
    artisan('config:clear');
    artisan('migrate:fresh', '--force');
    artisan('db:seed', '--force', '--class=Tests\\Support\\E2eSeeder');
    artisan('cache:clear');
    rmSync('./storage/e2e', { recursive: true, force: true });
    mkdirSync('./storage/e2e/screens', { recursive: true });
}
