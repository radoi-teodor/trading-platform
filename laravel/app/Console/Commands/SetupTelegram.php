<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupTelegram extends Command
{
    protected $signature = 'telegram:setup';

    protected $description = 'Display instructions for setting up a Telegram bot for notifications';

    public function handle(): int
    {
        $this->newLine();
        $this->info('=== Telegram Bot Setup Instructions ===');
        $this->newLine();

        $this->line('1. Open Telegram and search for @BotFather');
        $this->line('2. Send /newbot and follow the prompts to create a new bot');
        $this->line('3. Copy the bot token provided by BotFather');
        $this->newLine();

        $this->line('4. To get your Chat ID:');
        $this->line('   a. Start a conversation with your new bot');
        $this->line('   b. Send any message to the bot');
        $this->line('   c. Open this URL in your browser (replace BOT_TOKEN):');
        $this->line('      https://api.telegram.org/botBOT_TOKEN/getUpdates');
        $this->line('   d. Look for "chat":{"id":YOUR_CHAT_ID} in the response');
        $this->newLine();

        $this->line('5. In the Trading Platform:');
        $this->line('   a. Go to Settings > Notifications');
        $this->line('   b. Add a new Telegram channel');
        $this->line('   c. Enter the bot token and chat ID');
        $this->line('   d. Click "Test" to verify the connection');
        $this->newLine();

        $this->info('For group notifications:');
        $this->line('   - Add the bot to your group');
        $this->line('   - The chat ID for groups is negative (e.g., -123456789)');
        $this->newLine();

        return self::SUCCESS;
    }
}
