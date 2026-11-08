# Glossary

Trading terms in plain words. A research agent reads this file when it turns a description into DSL, so the
"Plain words" column includes the Romanian phrases an operator is likely to use. Add a row whenever a
description had to be clarified twice.

| Term | Meaning | DSL | Plain words (EN / RO) |
|------|---------|-----|------------------------|
| Candle, bar | Open, high, low, close and volume over one timeframe | `open`, `high`, `low`, `close`, `volume` | candle / lumânare |
| Timeframe | Length of one candle | `timeframe: 4h`, `on 1d` | on the 4-hour chart / pe graficul de 4 ore |
| Long | Buy, profit if the price rises | `entries.long` | buy, bet on a rise / cumpăr, pariez pe creștere |
| Short | Sell first, profit if the price falls; perpetuals only here | `entries.short` | bet on a fall / pariez pe scădere |
| Spot | Buying the coin itself | `market.type: spot` | the coin itself / moneda propriu-zisă |
| Perpetual future | A contract that tracks the price, with leverage and funding, no expiry | `market.type: perp` | futures, perpetual / futures, perpetual |
| Funding | Periodic payment between longs and shorts on perpetuals | `funding_rate` | funding / dobânda de funding |
| Leverage | Position larger than the margin behind it | `max_leverage_perp` | leverage / efect de levier |
| Stop-loss | Exit that limits the loss | `exits.stop_loss` | cut the loss / tai pierderea, stop |
| Take profit | Exit that locks a gain | `exits.take_profit` | take the gain / iau profitul |
| Trailing stop | A stop that follows the price and never moves back | `exits.trailing` | stop that follows / stop care urcă după preț |
| R | The money at risk on one trade (entry to stop) | `3R` | units of risk / unități de risc |
| Risk per trade | Share of equity lost if the stop is hit | `sizing.risk_per_trade` | how much I can lose per trade / cât pot pierde pe o tranzacție |
| Position size | How much to buy; computed from risk and stop distance | computed | how much to buy / cât cumpăr |
| Moving average | Average of the last n closes; EMA weighs recent ones more | `sma(n)`, `ema(n)` | average / medie mobilă |
| Cross above | One line moving from below to above another | `crosses_above` | crosses over / trece peste |
| Trend | Persistent direction of price | `ema(20) > ema(50)`, `adx(14) > 20` | trend, it keeps rising / trend, crește constant |
| Range | Price moving sideways between levels | `adx(14) < 20` | sideways, calm / lateral, liniștit |
| Volatility | How much the price moves | `atr(14)`, `realized_vol(n)`, `bb(20, 2).width` | how wild it is / cât de agitat e |
| ATR | Average true range: typical candle size | `atr(14)` | normal move size / mărimea obișnuită a unei mișcări |
| RSI | Momentum oscillator 0 to 100; under 30 often called oversold | `rsi(14)` | oversold, overbought / supravândut, supracumpărat |
| Bollinger bands | Average plus and minus k standard deviations | `bb(20, 2)` | far from normal / departe de normal, "a scăzut mult" |
| Breakout | Price leaving a recent range | `close > highest(high, 20)[1]` | breaks out / iese din interval, sparge rezistența |
| Mean reversion | Betting that a stretched price returns to its average | `family: mean_reversion` | it comes back / revine la medie |
| Drawdown | Fall from the highest equity so far | risk policy | how far below the peak / cât am scăzut de la maxim |
| Expectancy | Average result per trade after costs | reports | average gain per trade / câștig mediu pe tranzacție |
| Profit factor | Gross profit divided by gross loss | reports | profit vs loss ratio / raportul câștig-pierdere |
| Slippage | Difference between expected and actual fill price | cost model | worse price than planned / preț mai prost decât planificat |
| Maker, taker | Adding a resting order vs taking liquidity; different fees | `order: limit` / `market` | limit vs market order / ordin limită vs la piață |
| Regime | The market's current character, labeled by rules in backtest reports (D-58) | `adx(14)`, `natr(14)` | market mood / starea pieței |
| Carry | Earning a periodic payment (funding) while hedged against price | `template: funding_carry` | collect funding hedged / încasez funding acoperit |
| Paper trading | Trading on live prices with simulated money | stage `paper` | demo / pe hârtie, demo |
| Walk-forward | Tune on one period, test on the next, repeat | simulation | test on unseen data / test pe date nevăzute |
| Overfitting | Rules that fit the past by accident and fail later | gates | curve fitting / potrivit pe trecut |
