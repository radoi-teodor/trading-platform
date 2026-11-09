<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Accounts\OpenAccount;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use InvalidArgumentException;

/** Creates a paper or testnet account (docs/engine.md, section 9). Live accounts come with phase 6 (D-16). */
final class CreateAccount extends Command
{
    protected $signature = 'account:create
        {name : Lowercase letters, digits and hyphens, such as paper}
        {--mode=paper : paper or testnet}
        {--equity=10000 : Starting USDT, all in the spot wallet unless --futures is given}
        {--futures=0 : Starting USDT in the USD-M wallet}
        {--symbol=* : Instruments to follow besides the strategies\', such as BTC/USDT (default BTC/USDT)}
        {--start= : When trading starts, UTC (default: the current minute)}';

    protected $description = 'Create a paper or testnet account';

    public function handle(OpenAccount $accounts): int
    {
        try {
            $text = fn (string $option, string $default): string => is_string($this->option($option)) ? $this->option($option) : $default;
            $symbols = $this->option('symbol');
            $start = is_string($this->option('start')) ? CarbonImmutable::parse($this->option('start'), 'UTC') : null;
            $account = $accounts->open(
                is_string($this->argument('name')) ? $this->argument('name') : '', $text('mode', 'paper'), $text('equity', '10000'), $text('futures', '0'),
                is_array($symbols) ? array_values(array_map(fn (mixed $s): string => is_string($s) ? $s : '', $symbols)) : [], $start,
            );
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        $this->info("{$account->name} ({$account->mode->value}) starts at {$account->started_at->toIso8601ZuluString()}. Run it with php artisan engine:run {$account->name}.");

        return self::SUCCESS;
    }
}
