<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Demo user
        $userId = DB::table('users')->insertGetId([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'created_at' => $now->copy()->subDays(30),
            'updated_at' => $now,
        ]);

        // 2. User settings
        DB::table('user_settings')->insert([
            'user_id' => $userId,
            'risk_tolerance' => 'moderate',
            'max_daily_trades' => 10,
            'max_position_pct' => 20,
            'max_daily_loss_pct' => 5,
            'preferred_markets' => json_encode(['us_stocks', 'crypto']),
            'custom_instructions' => 'Focus on tech momentum plays and high-volume stocks. Avoid biotech and penny stocks. Keep positions for 1-3 days maximum.',
            'analysis_schedule' => 'premarket',
            'auto_execute' => true,
            'timezone' => 'America/New_York',
            'created_at' => $now->copy()->subDays(30),
            'updated_at' => $now,
        ]);

        // 3. Portfolio
        $portfolioId = DB::table('portfolios')->insertGetId([
            'user_id' => $userId,
            'name' => 'Main Portfolio',
            'balance_cents' => 10000000,
            'initial_balance_cents' => 10000000,
            'created_at' => $now->copy()->subDays(30),
            'updated_at' => $now,
        ]);

        // 4. Scrapers
        $scraperId1 = DB::table('scrapers')->insertGetId([
            'user_id' => $userId,
            'name' => 'Reuters Market News',
            'url' => 'https://feeds.reuters.com/reuters/businessNews',
            'type' => 'rss',
            'selector_config' => json_encode([]),
            'schedule' => 'every_15_min',
            'keyword_filters' => json_encode(['earnings', 'market', 'stocks', 'fed']),
            'associated_symbols' => json_encode(['AAPL', 'MSFT', 'GOOGL', 'TSLA']),
            'is_active' => true,
            'agent_status' => 'running',
            'last_run_at' => $now->copy()->subMinutes(12),
            'articles_count' => 142,
            'created_at' => $now->copy()->subDays(14),
            'updated_at' => $now,
        ]);

        $scraperId2 = DB::table('scrapers')->insertGetId([
            'user_id' => $userId,
            'name' => 'TechCrunch Finance',
            'url' => 'https://techcrunch.com/tag/finance/',
            'type' => 'html',
            'selector_config' => json_encode([
                'title' => 'h2.post-block__title a',
                'content' => 'div.post-block__content',
                'link' => 'h2.post-block__title a@href',
            ]),
            'schedule' => 'every_1_hour',
            'keyword_filters' => json_encode(['startup', 'IPO', 'funding']),
            'associated_symbols' => json_encode(['AAPL', 'GOOGL']),
            'is_active' => true,
            'agent_status' => 'running',
            'last_run_at' => $now->copy()->subMinutes(45),
            'articles_count' => 67,
            'created_at' => $now->copy()->subDays(14),
            'updated_at' => $now,
        ]);

        // 5. Scraped articles (20 mock articles)
        $articles = [
            ['title' => 'Apple Reports Record Q4 Revenue Driven by iPhone 16 Sales', 'symbol' => 'AAPL', 'sentiment' => 'bullish', 'score' => 0.82, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 1],
            ['title' => 'Microsoft Azure Growth Slows as Cloud Competition Intensifies', 'symbol' => 'MSFT', 'sentiment' => 'bearish', 'score' => -0.45, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 2],
            ['title' => 'Tesla Cybertruck Deliveries Exceed Analyst Expectations', 'symbol' => 'TSLA', 'sentiment' => 'bullish', 'score' => 0.71, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 3],
            ['title' => 'Google Faces New Antitrust Probe in EU Over AI Practices', 'symbol' => 'GOOGL', 'sentiment' => 'bearish', 'score' => -0.38, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 4],
            ['title' => 'Bitcoin Surges Past $95,000 on ETF Inflow Optimism', 'symbol' => 'BTC-USD', 'sentiment' => 'bullish', 'score' => 0.89, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 5],
            ['title' => 'Fed Signals Potential Rate Cut in September Meeting Minutes', 'symbol' => 'AAPL', 'sentiment' => 'bullish', 'score' => 0.55, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 6],
            ['title' => 'Apple Vision Pro Sales Disappoint in International Markets', 'symbol' => 'AAPL', 'sentiment' => 'bearish', 'score' => -0.32, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 7],
            ['title' => 'MSFT Partners with OpenAI for Enterprise AI Solutions', 'symbol' => 'MSFT', 'sentiment' => 'bullish', 'score' => 0.67, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 8],
            ['title' => 'Tesla Announces New Gigafactory Location in Indonesia', 'symbol' => 'TSLA', 'sentiment' => 'bullish', 'score' => 0.58, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 9],
            ['title' => 'Alphabet Reports Mixed Earnings as Ad Revenue Plateaus', 'symbol' => 'GOOGL', 'sentiment' => 'neutral', 'score' => 0.05, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 10],
            ['title' => 'Tech Startup Raises $500M to Compete with Apple in Wearables', 'symbol' => 'AAPL', 'sentiment' => 'bearish', 'score' => -0.15, 'source' => 'TechCrunch', 'scraper_id' => $scraperId2, 'hours_ago' => 2],
            ['title' => 'Google DeepMind Achieves Breakthrough in Protein Folding AI', 'symbol' => 'GOOGL', 'sentiment' => 'bullish', 'score' => 0.73, 'source' => 'TechCrunch', 'scraper_id' => $scraperId2, 'hours_ago' => 4],
            ['title' => 'Bitcoin Mining Difficulty Reaches All-Time High', 'symbol' => 'BTC-USD', 'sentiment' => 'neutral', 'score' => -0.08, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 11],
            ['title' => 'Apple Acquires AI Chip Startup for $2 Billion', 'symbol' => 'AAPL', 'sentiment' => 'bullish', 'score' => 0.76, 'source' => 'TechCrunch', 'scraper_id' => $scraperId2, 'hours_ago' => 6],
            ['title' => 'Tesla Recalls 50,000 Vehicles Over Steering Issue', 'symbol' => 'TSLA', 'sentiment' => 'bearish', 'score' => -0.61, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 13],
            ['title' => 'Microsoft Copilot Adoption Reaches 100M Enterprise Users', 'symbol' => 'MSFT', 'sentiment' => 'bullish', 'score' => 0.69, 'source' => 'TechCrunch', 'scraper_id' => $scraperId2, 'hours_ago' => 8],
            ['title' => 'Crypto Market Cap Crosses $4 Trillion Milestone', 'symbol' => 'BTC-USD', 'sentiment' => 'bullish', 'score' => 0.84, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 15],
            ['title' => 'Google Cloud Revenue Growth Accelerates in Q3', 'symbol' => 'GOOGL', 'sentiment' => 'bullish', 'score' => 0.62, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 17],
            ['title' => 'Tesla FSD Beta v13 Shows Significant Safety Improvements', 'symbol' => 'TSLA', 'sentiment' => 'bullish', 'score' => 0.54, 'source' => 'TechCrunch', 'scraper_id' => $scraperId2, 'hours_ago' => 20],
            ['title' => 'Apple Services Revenue Hits New Record of $25B Quarterly', 'symbol' => 'AAPL', 'sentiment' => 'bullish', 'score' => 0.78, 'source' => 'Reuters', 'scraper_id' => $scraperId1, 'hours_ago' => 22],
        ];

        foreach ($articles as $article) {
            DB::table('scraped_articles')->insert([
                'scraper_id' => $article['scraper_id'],
                'title' => $article['title'],
                'content' => 'Full article content for: ' . $article['title'] . '. This is a detailed news article covering the latest developments in the financial markets. Analysts have weighed in with their perspectives on how this news could impact stock prices in the coming trading sessions. Market participants are closely watching for further developments.',
                'url' => 'https://example.com/news/' . strtolower(str_replace([' ', ',', '\'', '$'], ['-', '', '', ''], $article['title'])),
                'source' => $article['source'],
                'published_at' => $now->copy()->subHours($article['hours_ago']),
                'sentiment_score' => $article['score'],
                'sentiment_label' => $article['sentiment'],
                'symbols' => json_encode([$article['symbol']]),
                'fed_to_claude' => $article['hours_ago'] > 6,
                'created_at' => $now->copy()->subHours($article['hours_ago']),
                'updated_at' => $now->copy()->subHours($article['hours_ago']),
            ]);
        }

        // 6. Stock prices (30 days for 5 symbols)
        $basePrices = [
            'AAPL' => 18500,
            'MSFT' => 42000,
            'GOOGL' => 17500,
            'TSLA' => 24500,
            'BTC-USD' => 9500000,
        ];

        foreach ($basePrices as $symbol => $basePrice) {
            $currentPrice = $basePrice;
            for ($day = 30; $day >= 1; $day--) {
                $date = $now->copy()->subDays($day)->format('Y-m-d');
                $volatility = $symbol === 'BTC-USD' ? 0.035 : ($symbol === 'TSLA' ? 0.025 : 0.012);
                $change = $currentPrice * $volatility * (mt_rand(-100, 100) / 100);
                $open = (int) $currentPrice;
                $close = (int) ($currentPrice + $change);
                $high = (int) max($open, $close) + abs((int) ($currentPrice * $volatility * mt_rand(10, 50) / 100));
                $low = (int) min($open, $close) - abs((int) ($currentPrice * $volatility * mt_rand(10, 50) / 100));
                $volume = $symbol === 'BTC-USD' ? mt_rand(20000, 80000) : mt_rand(10000000, 80000000);

                DB::table('stock_prices')->insert([
                    'symbol' => $symbol,
                    'open' => $open,
                    'high' => $high,
                    'low' => $low,
                    'close' => $close,
                    'volume' => $volume,
                    'date' => $date,
                ]);

                $currentPrice = $close;
            }
        }

        // 7. Watchlist
        $watchlistId = DB::table('watchlists')->insertGetId([
            'user_id' => $userId,
            'created_at' => $now->copy()->subDays(14),
            'updated_at' => $now,
        ]);

        foreach (['AAPL', 'MSFT', 'GOOGL', 'TSLA', 'BTC-USD'] as $symbol) {
            DB::table('watchlist_items')->insert([
                'watchlist_id' => $watchlistId,
                'symbol' => $symbol,
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now,
            ]);
        }

        // 8. Claude analyses (3 days)
        $day1Date = $now->copy()->subDays(3);
        $day2Date = $now->copy()->subDays(2);
        $day3Date = $now->copy()->subDays(1);

        // Analysis Day 1 - Bullish
        $analysis1Id = DB::table('claude_analyses')->insertGetId([
            'user_id' => $userId,
            'date' => $day1Date->format('Y-m-d'),
            'market_outlook' => 'bullish',
            'confidence' => 0.78,
            'reasoning' => "Market conditions are showing strong bullish signals across multiple indicators. The S&P 500 broke through key resistance at 5,200 with above-average volume, suggesting institutional participation. Tech sector momentum remains robust with AAPL and MSFT leading the charge after positive earnings revisions.\n\nThe Federal Reserve's latest minutes indicate a dovish tilt, with several members expressing openness to rate cuts in the coming months. This has pushed Treasury yields lower and created a favorable environment for growth stocks. The VIX has dropped below 14, indicating low fear in the market.\n\nSentiment analysis from our news scrapers shows a 65% bullish bias across major financial outlets. Notably, Reuters and Bloomberg have published several positive pieces on tech earnings expectations. The put/call ratio has fallen to 0.72, further confirming bullish positioning.\n\nHowever, we should remain cautious about overextending positions given the rapid pace of the rally. I recommend focusing on high-conviction plays with tight stop losses to protect gains.",
            'risk_assessment' => 'Moderate risk environment. Key risks include potential earnings misses from mega-cap tech, geopolitical tensions in the Middle East, and the possibility of hawkish Fed commentary. Position sizing should be conservative despite bullish outlook.',
            'lessons_from_yesterday' => null,
            'strategy_json' => json_encode([
                'approach' => 'momentum_continuation',
                'sectors' => ['technology', 'consumer_discretionary'],
                'position_size' => '15% per trade',
                'stop_loss_strategy' => '2% trailing stop',
                'time_horizon' => '1-3 days',
            ]),
            'status' => 'completed',
            'trades_planned' => 3,
            'trades_executed' => 2,
            'total_pnl_cents' => 45000,
            'prompt_sent' => "You are an AI trading analyst. Analyze the following market data and news for the trading day.\n\nCurrent Portfolio: $100,000 cash, no open positions.\nRisk Tolerance: Moderate\nPreferred Markets: US Stocks, Crypto\nCustom Instructions: Focus on tech momentum plays and high-volume stocks.\n\nRecent News Headlines:\n- Apple Reports Record Q4 Revenue Driven by iPhone 16 Sales (Bullish)\n- Microsoft Azure Growth Slows (Bearish)\n- Fed Signals Potential Rate Cut (Bullish)\n\nStock Data (last 5 days):\nAAPL: 184.50 -> 185.20 -> 186.00 -> 185.80 -> 187.10\nMSFT: 419.00 -> 420.50 -> 418.00 -> 419.80 -> 421.00\nTSLA: 244.00 -> 245.50 -> 243.00 -> 246.00 -> 248.00\n\nProvide your analysis and trade recommendations.",
            'raw_response' => json_encode([
                'market_outlook' => 'bullish',
                'confidence' => 0.78,
                'reasoning' => 'Strong technical and fundamental signals...',
                'trades' => [
                    ['symbol' => 'AAPL', 'action' => 'buy', 'quantity' => 10, 'entry_price' => 18710],
                    ['symbol' => 'TSLA', 'action' => 'buy', 'quantity' => 5, 'entry_price' => 24800],
                    ['symbol' => 'MSFT', 'action' => 'sell', 'quantity' => 3, 'entry_price' => 42100],
                ],
            ]),
            'next_review' => 'tomorrow',
            'created_at' => $day1Date->copy()->setTime(8, 30),
            'completed_at' => $day1Date->copy()->setTime(16, 0),
        ]);

        // Analysis Day 2 - Neutral
        $analysis2Id = DB::table('claude_analyses')->insertGetId([
            'user_id' => $userId,
            'date' => $day2Date->format('Y-m-d'),
            'market_outlook' => 'neutral',
            'confidence' => 0.62,
            'reasoning' => "Today's market presents a mixed picture that warrants a neutral stance. While the overall trend remains constructive, several conflicting signals suggest we should temper our expectations. The S&P 500 is consolidating near its highs after yesterday's strong move, and volume has declined notably.\n\nTech earnings from after-hours reports were mixed. MSFT beat on revenue but guided lower on cloud margins, creating uncertainty in the sector. AAPL continues to show relative strength, but the stock is approaching overbought territory on the RSI with a reading of 72.\n\nMacro data released this morning shows consumer confidence declining slightly, and initial jobless claims ticked higher than expected. These data points, while not alarming, suggest the economy may be cooling at the margin. The dollar index has strengthened, which could create headwinds for multinational tech companies.\n\nGiven these mixed signals, I recommend maintaining existing positions but being selective about new entries. We should focus on managing risk and taking partial profits on positions that have reached initial targets.",
            'risk_assessment' => 'Elevated uncertainty. Mixed earnings signals and deteriorating macro data create a challenging environment. Recommend reducing position sizes to 10% per trade and tightening stop losses to 1.5%.',
            'lessons_from_yesterday' => 'Yesterday\'s bullish call was partially correct - AAPL and TSLA performed well, but the MSFT sell signal was premature as the stock bounced before our entry. Lesson: Wait for confirmation of breakdown patterns before initiating short positions.',
            'strategy_json' => json_encode([
                'approach' => 'range_trading',
                'sectors' => ['technology'],
                'position_size' => '10% per trade',
                'stop_loss_strategy' => '1.5% hard stop',
                'time_horizon' => '1 day',
            ]),
            'status' => 'completed',
            'trades_planned' => 2,
            'trades_executed' => 2,
            'total_pnl_cents' => -12000,
            'prompt_sent' => "You are an AI trading analyst. Analyze the following market data and news for the trading day.\n\nCurrent Portfolio: $100,450 (after yesterday's gains)\nOpen Positions: AAPL long 10 shares @ $187.10, TSLA long 5 shares @ $248.00\nRisk Tolerance: Moderate\n\nYesterday's Performance: +$450 (2 of 3 trades executed successfully)\n\nRecent News Headlines:\n- Microsoft Azure Growth Slows as Cloud Competition Intensifies (Bearish)\n- Tesla Cybertruck Deliveries Exceed Analyst Expectations (Bullish)\n- Google Faces New Antitrust Probe (Bearish)\n\nProvide your analysis and trade recommendations.",
            'raw_response' => json_encode([
                'market_outlook' => 'neutral',
                'confidence' => 0.62,
                'reasoning' => 'Mixed signals across multiple indicators...',
                'trades' => [
                    ['symbol' => 'AAPL', 'action' => 'sell', 'quantity' => 5, 'entry_price' => 18850],
                    ['symbol' => 'GOOGL', 'action' => 'buy', 'quantity' => 8, 'entry_price' => 17600],
                ],
            ]),
            'next_review' => 'tomorrow',
            'created_at' => $day2Date->copy()->setTime(8, 30),
            'completed_at' => $day2Date->copy()->setTime(16, 0),
        ]);

        // Analysis Day 3 - Bullish
        $analysis3Id = DB::table('claude_analyses')->insertGetId([
            'user_id' => $userId,
            'date' => $day3Date->format('Y-m-d'),
            'market_outlook' => 'bullish',
            'confidence' => 0.81,
            'reasoning' => "Strong conviction in a bullish setup today driven by multiple converging catalysts. Overnight futures are pointing to a gap-up open after positive economic data from Europe and Asia. The Chinese PMI came in above expectations, boosting sentiment for global growth-sensitive sectors.\n\nApple announced a new $100B share buyback program in after-hours trading, the largest in corporate history. This is a powerful signal of management confidence and should provide a floor for the stock price. Combined with strong iPhone sales data from channel checks, AAPL is my highest conviction play today.\n\nTesla has broken above its 50-day moving average with increasing volume, a classic technical breakout pattern. The stock has been building a base for the past three weeks and appears ready for a move higher. Analyst upgrades from Goldman Sachs and Morgan Stanley are adding fuel to the rally.\n\nBitcoin is also showing strength, trading above $96,000 as institutional inflows into spot BTC ETFs continue to accelerate. On-chain data shows accumulation by large wallets, suggesting smart money is positioning for a move toward $100K. I recommend adding crypto exposure alongside our tech positions.\n\nThe only concern is the GOOGL position from yesterday which is underwater. I recommend holding it with a tight stop at $172.00 as the antitrust news may continue to weigh on sentiment.",
            'risk_assessment' => 'Moderate-low risk. Strong breadth and positive catalysts support the bullish thesis. Main risk is a sudden reversal in sentiment due to unexpected geopolitical events. Maintain 2% stop losses on all positions.',
            'lessons_from_yesterday' => 'The neutral stance yesterday was appropriate given the mixed signals. The partial profit-taking on AAPL was well-timed as the stock pulled back intraday before recovering. The GOOGL entry was poorly timed - should have waited for the antitrust news to be fully digested. Lesson: Avoid entering positions ahead of known regulatory catalysts.',
            'strategy_json' => json_encode([
                'approach' => 'breakout_momentum',
                'sectors' => ['technology', 'crypto'],
                'position_size' => '15% per trade',
                'stop_loss_strategy' => '2% trailing stop',
                'time_horizon' => '2-3 days',
            ]),
            'status' => 'active',
            'trades_planned' => 4,
            'trades_executed' => 3,
            'total_pnl_cents' => 78000,
            'prompt_sent' => "You are an AI trading analyst. Analyze the following market data and news for the trading day.\n\nCurrent Portfolio: $100,330 (after yesterday's losses)\nOpen Positions: AAPL long 5 shares @ $188.50, TSLA long 5 shares @ $248.00, GOOGL long 8 shares @ $176.00\nRisk Tolerance: Moderate\n\nYesterday's Performance: -$120 (2 trades executed, mixed results)\n\nRecent News Headlines:\n- Apple Announces $100B Buyback Program (Bullish)\n- Tesla FSD Beta v13 Shows Safety Improvements (Bullish)\n- Bitcoin Surges Past $95,000 on ETF Optimism (Bullish)\n- Google DeepMind Achieves AI Breakthrough (Bullish)\n\nProvide your analysis and trade recommendations.",
            'raw_response' => json_encode([
                'market_outlook' => 'bullish',
                'confidence' => 0.81,
                'reasoning' => 'Multiple converging bullish catalysts...',
                'trades' => [
                    ['symbol' => 'AAPL', 'action' => 'buy', 'quantity' => 10, 'entry_price' => 18900],
                    ['symbol' => 'TSLA', 'action' => 'buy', 'quantity' => 5, 'entry_price' => 25100],
                    ['symbol' => 'BTC-USD', 'action' => 'buy', 'quantity' => 0.01, 'entry_price' => 9600000],
                    ['symbol' => 'MSFT', 'action' => 'buy', 'quantity' => 5, 'entry_price' => 42200],
                ],
            ]),
            'next_review' => 'midday',
            'created_at' => $day3Date->copy()->setTime(8, 30),
            'completed_at' => null,
        ]);

        // 9. Claude analysis trades
        // Day 1 trades
        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis1Id,
            'symbol' => 'AAPL',
            'action' => 'buy',
            'order_type' => 'market',
            'quantity' => 10.00000000,
            'entry_price_cents' => 18710,
            'stop_loss_cents' => 18340,
            'take_profit_cents' => 19200,
            'urgency' => 'immediate',
            'confidence' => 0.82,
            'reasoning' => 'AAPL showing strong momentum after record Q4 revenue. iPhone 16 sales exceeding expectations and services revenue at all-time high. Technical breakout above $186 resistance with volume confirmation.',
            'status' => 'executed',
            'executed_order_id' => 1,
            'skip_reason' => null,
            'created_at' => $day1Date->copy()->setTime(9, 31),
            'updated_at' => $day1Date->copy()->setTime(9, 31),
        ]);

        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis1Id,
            'symbol' => 'TSLA',
            'action' => 'buy',
            'order_type' => 'market',
            'quantity' => 5.00000000,
            'entry_price_cents' => 24800,
            'stop_loss_cents' => 24300,
            'take_profit_cents' => 25800,
            'urgency' => 'immediate',
            'confidence' => 0.74,
            'reasoning' => 'TSLA Cybertruck deliveries exceeding expectations, new Gigafactory announcement adds to growth narrative. Stock testing 50-DMA breakout level with bullish MACD crossover.',
            'status' => 'executed',
            'executed_order_id' => 2,
            'skip_reason' => null,
            'created_at' => $day1Date->copy()->setTime(9, 32),
            'updated_at' => $day1Date->copy()->setTime(9, 32),
        ]);

        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis1Id,
            'symbol' => 'MSFT',
            'action' => 'sell',
            'order_type' => 'limit',
            'quantity' => 3.00000000,
            'entry_price_cents' => 42100,
            'stop_loss_cents' => 42500,
            'take_profit_cents' => 41200,
            'urgency' => 'wait_for_dip',
            'confidence' => 0.65,
            'reasoning' => 'MSFT Azure growth deceleration is concerning. Cloud margin compression could weigh on the stock. Waiting for a bounce to resistance at $421 before initiating short position.',
            'status' => 'skipped',
            'executed_order_id' => null,
            'skip_reason' => 'Price did not reach the limit entry level of $421.00 during the trading session. The stock traded in a narrow range between $418.50-$420.20.',
            'created_at' => $day1Date->copy()->setTime(9, 33),
            'updated_at' => $day1Date->copy()->setTime(16, 0),
        ]);

        // Day 2 trades
        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis2Id,
            'symbol' => 'AAPL',
            'action' => 'sell',
            'order_type' => 'market',
            'quantity' => 5.00000000,
            'entry_price_cents' => 18850,
            'stop_loss_cents' => 19000,
            'take_profit_cents' => 18500,
            'urgency' => 'immediate',
            'confidence' => 0.68,
            'reasoning' => 'Taking partial profits on AAPL as RSI approaches overbought territory at 72. Selling half the position to lock in gains while maintaining exposure to potential further upside.',
            'status' => 'executed',
            'executed_order_id' => 3,
            'skip_reason' => null,
            'created_at' => $day2Date->copy()->setTime(9, 35),
            'updated_at' => $day2Date->copy()->setTime(9, 35),
        ]);

        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis2Id,
            'symbol' => 'GOOGL',
            'action' => 'buy',
            'order_type' => 'market',
            'quantity' => 8.00000000,
            'entry_price_cents' => 17600,
            'stop_loss_cents' => 17200,
            'take_profit_cents' => 18200,
            'urgency' => 'immediate',
            'confidence' => 0.60,
            'reasoning' => 'GOOGL pullback on antitrust news creates a buying opportunity. Cloud revenue growth acceleration and DeepMind AI breakthroughs provide strong fundamental support. Entry near support at $176.',
            'status' => 'executed',
            'executed_order_id' => 4,
            'skip_reason' => null,
            'created_at' => $day2Date->copy()->setTime(10, 15),
            'updated_at' => $day2Date->copy()->setTime(10, 15),
        ]);

        // Day 3 trades
        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'AAPL',
            'action' => 'buy',
            'order_type' => 'market',
            'quantity' => 10.00000000,
            'entry_price_cents' => 18900,
            'stop_loss_cents' => 18520,
            'take_profit_cents' => 19500,
            'urgency' => 'immediate',
            'confidence' => 0.85,
            'reasoning' => 'Adding to AAPL position on $100B buyback announcement. This is the largest buyback in corporate history and provides a strong price floor. Combined with channel check data showing strong iPhone demand, high conviction entry.',
            'status' => 'executed',
            'executed_order_id' => 5,
            'skip_reason' => null,
            'created_at' => $day3Date->copy()->setTime(9, 31),
            'updated_at' => $day3Date->copy()->setTime(9, 31),
        ]);

        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'TSLA',
            'action' => 'buy',
            'order_type' => 'market',
            'quantity' => 5.00000000,
            'entry_price_cents' => 25100,
            'stop_loss_cents' => 24600,
            'take_profit_cents' => 26500,
            'urgency' => 'immediate',
            'confidence' => 0.76,
            'reasoning' => 'TSLA 50-DMA breakout confirmed with volume. FSD v13 safety improvements could be a catalyst for regulatory approval. Adding to position as technical and fundamental signals align.',
            'status' => 'executed',
            'executed_order_id' => 6,
            'skip_reason' => null,
            'created_at' => $day3Date->copy()->setTime(9, 32),
            'updated_at' => $day3Date->copy()->setTime(9, 32),
        ]);

        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'BTC-USD',
            'action' => 'buy',
            'order_type' => 'limit',
            'quantity' => 0.01000000,
            'entry_price_cents' => 9600000,
            'stop_loss_cents' => 9400000,
            'take_profit_cents' => 10000000,
            'urgency' => 'wait_for_dip',
            'confidence' => 0.72,
            'reasoning' => 'Bitcoin approaching $100K psychological level. Spot ETF inflows accelerating with on-chain accumulation signals. Waiting for a dip to $96K for optimal entry.',
            'status' => 'executed',
            'executed_order_id' => 7,
            'skip_reason' => null,
            'created_at' => $day3Date->copy()->setTime(10, 0),
            'updated_at' => $day3Date->copy()->setTime(14, 22),
        ]);

        DB::table('claude_analysis_trades')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'MSFT',
            'action' => 'buy',
            'order_type' => 'limit',
            'quantity' => 5.00000000,
            'entry_price_cents' => 42200,
            'stop_loss_cents' => 41400,
            'take_profit_cents' => 43500,
            'urgency' => 'end_of_day',
            'confidence' => 0.68,
            'reasoning' => 'MSFT Copilot adoption reaching 100M users is a strong growth signal. The Azure slowdown concerns are overdone. Looking for end-of-day entry if the stock holds above $420 support.',
            'status' => 'pending',
            'executed_order_id' => null,
            'skip_reason' => null,
            'created_at' => $day3Date->copy()->setTime(9, 33),
            'updated_at' => $day3Date->copy()->setTime(9, 33),
        ]);

        // 10. Positions
        // Open position: AAPL long 10 shares
        DB::table('positions')->insert([
            'portfolio_id' => $portfolioId,
            'symbol' => 'AAPL',
            'quantity' => 10.00000000,
            'avg_entry_price_cents' => 18900,
            'current_price_cents' => 19150,
            'side' => 'long',
            'opened_at' => $day3Date->copy()->setTime(9, 31),
            'closed_at' => null,
            'created_at' => $day3Date->copy()->setTime(9, 31),
            'updated_at' => $now,
        ]);

        // Open position: TSLA long 5 shares
        DB::table('positions')->insert([
            'portfolio_id' => $portfolioId,
            'symbol' => 'TSLA',
            'quantity' => 5.00000000,
            'avg_entry_price_cents' => 25100,
            'current_price_cents' => 25450,
            'side' => 'long',
            'opened_at' => $day3Date->copy()->setTime(9, 32),
            'closed_at' => null,
            'created_at' => $day3Date->copy()->setTime(9, 32),
            'updated_at' => $now,
        ]);

        // Closed position: GOOGL long 8 shares (from day 2, closed day 3)
        DB::table('positions')->insert([
            'portfolio_id' => $portfolioId,
            'symbol' => 'GOOGL',
            'quantity' => 8.00000000,
            'avg_entry_price_cents' => 17600,
            'current_price_cents' => 17480,
            'side' => 'long',
            'opened_at' => $day2Date->copy()->setTime(10, 15),
            'closed_at' => $day3Date->copy()->setTime(11, 0),
            'created_at' => $day2Date->copy()->setTime(10, 15),
            'updated_at' => $day3Date->copy()->setTime(11, 0),
        ]);

        // 11 & 12. Orders and Trades
        // Order 1: Buy AAPL (Day 1)
        $order1Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis1Id,
            'symbol' => 'AAPL',
            'type' => 'market',
            'side' => 'buy',
            'quantity' => 10.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day1Date->copy()->setTime(9, 31),
            'created_at' => $day1Date->copy()->setTime(9, 31),
            'updated_at' => $day1Date->copy()->setTime(9, 31),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order1Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'AAPL',
            'side' => 'buy',
            'quantity' => 10.00000000,
            'price_cents' => 18710,
            'pnl_cents' => 0,
            'fee_cents' => 100,
            'executed_at' => $day1Date->copy()->setTime(9, 31),
            'created_at' => $day1Date->copy()->setTime(9, 31),
            'updated_at' => $day1Date->copy()->setTime(9, 31),
        ]);

        // Order 2: Buy TSLA (Day 1)
        $order2Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis1Id,
            'symbol' => 'TSLA',
            'type' => 'market',
            'side' => 'buy',
            'quantity' => 5.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day1Date->copy()->setTime(9, 32),
            'created_at' => $day1Date->copy()->setTime(9, 32),
            'updated_at' => $day1Date->copy()->setTime(9, 32),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order2Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'TSLA',
            'side' => 'buy',
            'quantity' => 5.00000000,
            'price_cents' => 24800,
            'pnl_cents' => 0,
            'fee_cents' => 100,
            'executed_at' => $day1Date->copy()->setTime(9, 32),
            'created_at' => $day1Date->copy()->setTime(9, 32),
            'updated_at' => $day1Date->copy()->setTime(9, 32),
        ]);

        // Order 3: Sell partial AAPL (Day 2)
        $order3Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis2Id,
            'symbol' => 'AAPL',
            'type' => 'market',
            'side' => 'sell',
            'quantity' => 5.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day2Date->copy()->setTime(9, 35),
            'created_at' => $day2Date->copy()->setTime(9, 35),
            'updated_at' => $day2Date->copy()->setTime(9, 35),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order3Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'AAPL',
            'side' => 'sell',
            'quantity' => 5.00000000,
            'price_cents' => 18850,
            'pnl_cents' => 7000,
            'fee_cents' => 100,
            'executed_at' => $day2Date->copy()->setTime(9, 35),
            'created_at' => $day2Date->copy()->setTime(9, 35),
            'updated_at' => $day2Date->copy()->setTime(9, 35),
        ]);

        // Order 4: Buy GOOGL (Day 2)
        $order4Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis2Id,
            'symbol' => 'GOOGL',
            'type' => 'market',
            'side' => 'buy',
            'quantity' => 8.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day2Date->copy()->setTime(10, 15),
            'created_at' => $day2Date->copy()->setTime(10, 15),
            'updated_at' => $day2Date->copy()->setTime(10, 15),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order4Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'GOOGL',
            'side' => 'buy',
            'quantity' => 8.00000000,
            'price_cents' => 17600,
            'pnl_cents' => 0,
            'fee_cents' => 100,
            'executed_at' => $day2Date->copy()->setTime(10, 15),
            'created_at' => $day2Date->copy()->setTime(10, 15),
            'updated_at' => $day2Date->copy()->setTime(10, 15),
        ]);

        // Order 5: Buy AAPL (Day 3)
        $order5Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'AAPL',
            'type' => 'market',
            'side' => 'buy',
            'quantity' => 10.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day3Date->copy()->setTime(9, 31),
            'created_at' => $day3Date->copy()->setTime(9, 31),
            'updated_at' => $day3Date->copy()->setTime(9, 31),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order5Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'AAPL',
            'side' => 'buy',
            'quantity' => 10.00000000,
            'price_cents' => 18900,
            'pnl_cents' => 0,
            'fee_cents' => 100,
            'executed_at' => $day3Date->copy()->setTime(9, 31),
            'created_at' => $day3Date->copy()->setTime(9, 31),
            'updated_at' => $day3Date->copy()->setTime(9, 31),
        ]);

        // Order 6: Buy TSLA (Day 3)
        $order6Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'TSLA',
            'type' => 'market',
            'side' => 'buy',
            'quantity' => 5.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day3Date->copy()->setTime(9, 32),
            'created_at' => $day3Date->copy()->setTime(9, 32),
            'updated_at' => $day3Date->copy()->setTime(9, 32),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order6Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'TSLA',
            'side' => 'buy',
            'quantity' => 5.00000000,
            'price_cents' => 25100,
            'pnl_cents' => 0,
            'fee_cents' => 100,
            'executed_at' => $day3Date->copy()->setTime(9, 32),
            'created_at' => $day3Date->copy()->setTime(9, 32),
            'updated_at' => $day3Date->copy()->setTime(9, 32),
        ]);

        // Order 7: Buy BTC-USD (Day 3)
        $order7Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'BTC-USD',
            'type' => 'limit',
            'side' => 'buy',
            'quantity' => 0.01000000,
            'price_cents' => 9600000,
            'status' => 'filled',
            'filled_at' => $day3Date->copy()->setTime(14, 22),
            'created_at' => $day3Date->copy()->setTime(10, 0),
            'updated_at' => $day3Date->copy()->setTime(14, 22),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order7Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'BTC-USD',
            'side' => 'buy',
            'quantity' => 0.01000000,
            'price_cents' => 9600000,
            'pnl_cents' => 0,
            'fee_cents' => 500,
            'executed_at' => $day3Date->copy()->setTime(14, 22),
            'created_at' => $day3Date->copy()->setTime(14, 22),
            'updated_at' => $day3Date->copy()->setTime(14, 22),
        ]);

        // Order 8: Sell GOOGL (Day 3 - closing position)
        $order8Id = DB::table('orders')->insertGetId([
            'portfolio_id' => $portfolioId,
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'GOOGL',
            'type' => 'market',
            'side' => 'sell',
            'quantity' => 8.00000000,
            'price_cents' => null,
            'status' => 'filled',
            'filled_at' => $day3Date->copy()->setTime(11, 0),
            'created_at' => $day3Date->copy()->setTime(11, 0),
            'updated_at' => $day3Date->copy()->setTime(11, 0),
        ]);

        DB::table('trades')->insert([
            'order_id' => $order8Id,
            'portfolio_id' => $portfolioId,
            'symbol' => 'GOOGL',
            'side' => 'sell',
            'quantity' => 8.00000000,
            'price_cents' => 17480,
            'pnl_cents' => -9600,
            'fee_cents' => 100,
            'executed_at' => $day3Date->copy()->setTime(11, 0),
            'created_at' => $day3Date->copy()->setTime(11, 0),
            'updated_at' => $day3Date->copy()->setTime(11, 0),
        ]);

        // Analysis close record for GOOGL
        DB::table('claude_analysis_closes')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'GOOGL',
            'reason' => 'Antitrust concerns continue to weigh on sentiment. Stop loss level at $174.80 was breached. Closing position to limit losses and reallocate capital to higher conviction plays.',
            'urgency' => 'immediate',
            'status' => 'closed',
            'created_at' => $day3Date->copy()->setTime(11, 0),
            'updated_at' => $day3Date->copy()->setTime(11, 0),
        ]);

        // Analysis alerts
        DB::table('claude_analysis_alerts')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'AAPL',
            'condition_text' => 'If AAPL drops below $187.00, consider adding to position',
            'parsed_condition' => json_encode(['type' => 'price_below', 'symbol' => 'AAPL', 'price_cents' => 18700]),
            'triggered' => false,
            'triggered_at' => null,
            'result_order_id' => null,
            'created_at' => $day3Date->copy()->setTime(9, 30),
            'updated_at' => $day3Date->copy()->setTime(9, 30),
        ]);

        DB::table('claude_analysis_alerts')->insert([
            'claude_analysis_id' => $analysis3Id,
            'symbol' => 'BTC-USD',
            'condition_text' => 'If BTC breaks above $98,000, add another 0.005 BTC',
            'parsed_condition' => json_encode(['type' => 'price_above', 'symbol' => 'BTC-USD', 'price_cents' => 9800000]),
            'triggered' => false,
            'triggered_at' => null,
            'result_order_id' => null,
            'created_at' => $day3Date->copy()->setTime(9, 30),
            'updated_at' => $day3Date->copy()->setTime(9, 30),
        ]);

        // 13. Portfolio snapshots (last 7 days)
        $snapshotData = [
            ['days_ago' => 7, 'total' => 10000000, 'cash' => 10000000, 'positions' => 0],
            ['days_ago' => 6, 'total' => 10000000, 'cash' => 10000000, 'positions' => 0],
            ['days_ago' => 5, 'total' => 10000000, 'cash' => 10000000, 'positions' => 0],
            ['days_ago' => 4, 'total' => 10012000, 'cash' => 10000000, 'positions' => 12000],
            ['days_ago' => 3, 'total' => 10045000, 'cash' => 7541000, 'positions' => 2504000],
            ['days_ago' => 2, 'total' => 10033000, 'cash' => 6498700, 'positions' => 3534300],
            ['days_ago' => 1, 'total' => 10111000, 'cash' => 4967600, 'positions' => 5143400],
        ];

        foreach ($snapshotData as $snapshot) {
            DB::table('portfolio_snapshots')->insert([
                'portfolio_id' => $portfolioId,
                'total_value_cents' => $snapshot['total'],
                'cash_cents' => $snapshot['cash'],
                'positions_value_cents' => $snapshot['positions'],
                'date' => $now->copy()->subDays($snapshot['days_ago'])->format('Y-m-d'),
            ]);
        }

        // 14. Notification channel
        DB::table('notification_channels')->insert([
            'user_id' => $userId,
            'type' => 'email',
            'config' => json_encode(['email' => 'demo@example.com']),
            'is_active' => true,
            'created_at' => $now->copy()->subDays(14),
            'updated_at' => $now,
        ]);

        // 15. Notification rules
        $notificationEvents = ['trade_executed', 'stop_loss_hit', 'daily_summary'];
        foreach ($notificationEvents as $event) {
            DB::table('notification_rules')->insert([
                'user_id' => $userId,
                'event_type' => $event,
                'channels' => json_encode(['email']),
                'is_active' => true,
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now,
            ]);
        }

        // 16. Broker credential
        DB::table('broker_credentials')->insert([
            'user_id' => $userId,
            'broker' => 'paper',
            'credentials' => json_encode(['mode' => 'paper', 'initial_balance' => 10000000]),
            'is_active' => true,
            'created_at' => $now->copy()->subDays(30),
            'updated_at' => $now,
        ]);

        // 17. Bot logs (~15 entries)
        $botLogs = [
            ['hours_ago' => 48, 'level' => 'info', 'message' => 'Trading bot started for user Demo User. Paper broker connected.', 'context' => ['broker' => 'paper', 'portfolio_id' => $portfolioId]],
            ['hours_ago' => 47, 'level' => 'info', 'message' => 'Premarket analysis initiated. Fetching latest news and market data.', 'context' => ['scrapers_active' => 2, 'symbols_tracked' => 5]],
            ['hours_ago' => 46.5, 'level' => 'info', 'message' => 'Claude analysis completed. Market outlook: bullish (confidence: 0.78). 3 trades planned.', 'context' => ['analysis_id' => $analysis1Id, 'outlook' => 'bullish', 'trades_planned' => 3]],
            ['hours_ago' => 46, 'level' => 'trade', 'message' => 'Order executed: BUY 10 AAPL @ $187.10 (market order). Analysis confidence: 0.82.', 'context' => ['order_id' => $order1Id, 'symbol' => 'AAPL', 'price_cents' => 18710, 'quantity' => 10]],
            ['hours_ago' => 45.9, 'level' => 'trade', 'message' => 'Order executed: BUY 5 TSLA @ $248.00 (market order). Analysis confidence: 0.74.', 'context' => ['order_id' => $order2Id, 'symbol' => 'TSLA', 'price_cents' => 24800, 'quantity' => 5]],
            ['hours_ago' => 40, 'level' => 'info', 'message' => 'MSFT limit sell order skipped - price did not reach entry level of $421.00 during session.', 'context' => ['symbol' => 'MSFT', 'target_price_cents' => 42100, 'session_high_cents' => 42020]],
            ['hours_ago' => 38, 'level' => 'info', 'message' => 'End of day summary: 2/3 trades executed. Day P&L: +$450.00. Portfolio value: $100,450.', 'context' => ['pnl_cents' => 45000, 'trades_executed' => 2, 'trades_planned' => 3]],
            ['hours_ago' => 24, 'level' => 'info', 'message' => 'Premarket analysis initiated. Yesterday P&L: +$450.00. Adjusting strategy based on lessons learned.', 'context' => ['previous_pnl_cents' => 45000]],
            ['hours_ago' => 23.5, 'level' => 'info', 'message' => 'Claude analysis completed. Market outlook: neutral (confidence: 0.62). 2 trades planned.', 'context' => ['analysis_id' => $analysis2Id, 'outlook' => 'neutral', 'trades_planned' => 2]],
            ['hours_ago' => 23, 'level' => 'trade', 'message' => 'Order executed: SELL 5 AAPL @ $188.50 (partial profit taking). Realized P&L: +$70.00.', 'context' => ['order_id' => $order3Id, 'symbol' => 'AAPL', 'price_cents' => 18850, 'pnl_cents' => 7000]],
            ['hours_ago' => 22, 'level' => 'trade', 'message' => 'Order executed: BUY 8 GOOGL @ $176.00 (market order). Buying the dip on antitrust news.', 'context' => ['order_id' => $order4Id, 'symbol' => 'GOOGL', 'price_cents' => 17600, 'quantity' => 8]],
            ['hours_ago' => 14, 'level' => 'warning', 'message' => 'GOOGL position approaching stop loss level. Current: $174.80, Stop: $172.00. Monitoring closely.', 'context' => ['symbol' => 'GOOGL', 'current_price_cents' => 17480, 'stop_loss_cents' => 17200]],
            ['hours_ago' => 8, 'level' => 'info', 'message' => 'Claude analysis completed. Market outlook: bullish (confidence: 0.81). 4 trades planned.', 'context' => ['analysis_id' => $analysis3Id, 'outlook' => 'bullish', 'trades_planned' => 4]],
            ['hours_ago' => 7, 'level' => 'trade', 'message' => 'Order executed: BUY 10 AAPL @ $189.00 (market order). Adding on $100B buyback announcement.', 'context' => ['order_id' => $order5Id, 'symbol' => 'AAPL', 'price_cents' => 18900, 'quantity' => 10]],
            ['hours_ago' => 3, 'level' => 'info', 'message' => 'Reuters scraper fetched 8 new articles. 3 bullish, 2 bearish, 3 neutral. Feeding to Claude for next analysis.', 'context' => ['scraper_id' => $scraperId1, 'articles_fetched' => 8, 'sentiment_breakdown' => ['bullish' => 3, 'bearish' => 2, 'neutral' => 3]]],
        ];

        foreach ($botLogs as $log) {
            DB::table('bot_logs')->insert([
                'user_id' => $userId,
                'level' => $log['level'],
                'message' => $log['message'],
                'context' => json_encode($log['context']),
                'created_at' => $now->copy()->subHours($log['hours_ago']),
            ]);
        }

        // Agent health records
        DB::table('agent_health')->insert([
            'agent_type' => 'scraper',
            'agent_id' => 'scraper-' . $scraperId1,
            'user_id' => $userId,
            'status' => 'running',
            'last_heartbeat_at' => $now->copy()->subMinutes(2),
            'metadata' => json_encode(['scraper_name' => 'Reuters Market News', 'articles_today' => 8]),
        ]);

        DB::table('agent_health')->insert([
            'agent_type' => 'scraper',
            'agent_id' => 'scraper-' . $scraperId2,
            'user_id' => $userId,
            'status' => 'running',
            'last_heartbeat_at' => $now->copy()->subMinutes(5),
            'metadata' => json_encode(['scraper_name' => 'TechCrunch Finance', 'articles_today' => 3]),
        ]);

        DB::table('agent_health')->insert([
            'agent_type' => 'trading_bot',
            'agent_id' => 'bot-user-' . $userId,
            'user_id' => $userId,
            'status' => 'running',
            'last_heartbeat_at' => $now->copy()->subSeconds(30),
            'metadata' => json_encode(['uptime_hours' => 48, 'trades_today' => 3, 'portfolio_id' => $portfolioId]),
        ]);
    }
}
