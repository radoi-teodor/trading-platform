<?php

declare(strict_types=1);

namespace App\Execution\Console;

use App\Execution\Data\EngineMode;
use App\Execution\Models\Account;
use App\Support\Clock\Clock;
use App\Support\Number\Decimal;
use App\Support\Symbol;
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

    public function handle(Clock $clock): int
    {
        try {
            $name = $this->argument('name');
            $name = is_string($name) && preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $name) === 1 && strlen($name) <= 40 ? $name : throw new InvalidArgumentException('An account name is lowercase letters, digits and hyphens.');
            $mode = EngineMode::tryFrom(is_string($this->option('mode')) ? $this->option('mode') : '');

            if ($mode !== EngineMode::Paper && $mode !== EngineMode::Testnet) {
                throw new InvalidArgumentException('An account is paper or testnet; live accounts need phase 6 and the operator\'s approval (D-16).');
            }

            if (Account::query()->where('name', $name)->exists()) {
                throw new InvalidArgumentException("{$name} already exists.");
            }

            $amount = fn (string $option): string => is_string($this->option($option)) && preg_match('/^\d+(\.\d+)?$/', $this->option($option)) === 1
                ? (string) Decimal::of($this->option($option))
                : throw new InvalidArgumentException("--{$option} is an amount of USDT.");
            $symbols = $this->option('symbol');
            $symbols = is_array($symbols) && $symbols !== [] ? $symbols : ['BTC/USDT'];
            $start = is_string($this->option('start')) ? CarbonImmutable::parse($this->option('start'), 'UTC')->startOfMinute() : $clock->now()->startOfMinute();
        } catch (InvalidArgumentException $failure) {
            $this->error($failure->getMessage());

            return self::FAILURE;
        }

        Account::query()->create([
            'name' => $name, 'mode' => $mode, 'quote' => 'USDT', 'spot_balance' => $amount('equity'), 'futures_balance' => $amount('futures'),
            'symbols' => array_values(array_map(fn (mixed $s): string => (string) Symbol::parse(is_string($s) ? $s : ''), $symbols)),
            'started_at' => $start, 'created_at' => $clock->now(),
        ]);
        $this->info("{$name} ({$mode->value}) starts at {$start->toIso8601ZuluString()}. Run it with php artisan engine:run {$name}.");

        return self::SUCCESS;
    }
}
