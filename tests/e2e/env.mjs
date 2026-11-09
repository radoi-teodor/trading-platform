// The environment of the app under test: the scratch database and the fake Telegram. Nothing here is a secret.
export const BASE_URL = 'http://127.0.0.1:8123';
export const TELEGRAM_URL = 'http://127.0.0.1:8124';

export const E2E_ENV = {
    ...process.env,
    APP_ENV: 'local',
    APP_DEBUG: 'true',
    APP_URL: BASE_URL,
    DB_CONNECTION: 'pgsql',
    DB_DATABASE: 'trading_e2e',
    CACHE_STORE: 'file',
    SESSION_DRIVER: 'file',
    QUEUE_CONNECTION: 'database',
    NOTIFICATIONS_QUEUE: 'database',
    TELEGRAM_BOT_TOKEN: '',
    TELEGRAM_CHAT_ID: '',
    TELEGRAM_BASE_URL: TELEGRAM_URL,
    LOG_CHANNEL: 'single',
};
