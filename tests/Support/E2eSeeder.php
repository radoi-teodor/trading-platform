<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Execution\Accounts\OpenAccount;
use App\Market\CandleRepository;
use App\Market\Data\CandleSource;
use App\Market\Data\MarketType;
use App\Market\Models\Instrument;
use App\Market\Models\InstrumentFilter;
use App\Models\User;
use App\Simulation\StrategyLibrary;
use App\Support\Symbol;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Seeder;
use LogicException;

/**
 * The end-to-end suite's data (tests/e2e): an operator with a fixed password and TOTP secret, BTC/USDT spot with the
 * golden candles of 2026-10-01 to 10, a paper account and one strategy version. It refuses any database whose name
 * does not end in `_e2e`: the fixed credentials never reach a real database (docs/security.md, section 3).
 */
final class E2eSeeder extends Seeder
{
    public const string EMAIL = 'e2e@local.test';

    public const string PASSWORD = 'e2e local password only';

    /** Base32, the same in tests/e2e/support.mjs. */
    public const string TOTP_SECRET = 'JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP';

    public const string STRATEGY = <<<'YAML'
        strategy: ema-cross-e2e
        title: EMA cross on 15m
        family: trend
        hypothesis: Short-term momentum continues after a cross.
        fails_when: Chop.
        params:
          fast: { default: 9, range: [5, 13], step: 2 }
          slow: { default: 21, range: [15, 30], step: 3 }
        market: { exchange: binance, type: spot, instruments: [BTC/USDT] }
        timeframe: 15m
        entries:
          long:
            when: ema($fast) crosses_above ema($slow)
        exits:
          stop_loss: 1.5 * atr(14)
          take_profit: 2R
          when: ema($fast) crosses_below ema($slow)
        YAML;

    public function run(Repository $config, CandleRepository $candles, OpenAccount $accounts, StrategyLibrary $library): void
    {
        $connection = $config->get('database.default');
        $database = $config->get('database.connections.'.(is_string($connection) ? $connection : '').'.database');

        if (! is_string($database) || ! str_ends_with($database, '_e2e')) {
            throw new LogicException('The end-to-end seeder runs only on a database whose name ends in _e2e.');
        }

        $user = new User(['name' => 'Operator', 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $user->forceFill(['two_factor_secret' => encrypt(self::TOTP_SECRET), 'two_factor_recovery_codes' => encrypt('[]'), 'two_factor_confirmed_at' => CarbonImmutable::parse('2026-11-01', 'UTC')])->save();

        $seen = CarbonImmutable::parse('2026-11-01', 'UTC');
        $btc = Instrument::register('binance', MarketType::Spot, Symbol::parse('BTC/USDT'), $seen);
        InstrumentFilter::query()->create([
            'instrument_id' => $btc->id, 'valid_from' => $seen, 'tick_size' => '0.01', 'step_size' => '0.00001',
            'min_qty' => '0.00001', 'max_qty' => '9000', 'min_notional' => '5', 'raw' => [], 'created_at' => $seen,
        ]);
        $candles->upsert($btc, CandleSource::Archive, GoldenData::candles('BTCUSDT'));

        $accounts->open('paper', 'paper', '10000', '0', ['BTC/USDT'], CarbonImmutable::parse('2026-10-05', 'UTC'));
        $library->versions()->save(self::STRATEGY, 'operator');
    }
}
