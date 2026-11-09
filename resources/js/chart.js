// The chart of docs/brand.md, A5: candles in up/down, volume at reduced opacity, price layers over the candles in
// series slots 1, 3 and 4 (2 and 5 sit too close to `down`), other layers in panels below, conditions as markers,
// the window's volume profile as bars on the right. Colors come from the tokens, never from literals.
// Live (D-87): every second the bar still forming moves; candles and layers are recomputed when a candle closes.
import { createChart, CandlestickSeries, HistogramSeries, LineSeries, createSeriesMarkers } from 'lightweight-charts';

const token = (name) => getComputedStyle(document.documentElement).getPropertyValue(`--${name}`).trim();
const time = (iso) => Math.floor(Date.parse(iso) / 1000);
// A token at reduced opacity, for the volume bars: an alpha suffix on the token's hex value, since the chart library
// does not read color-mix().
const faded = (name, alpha) => token(name) + Math.round(alpha * 255).toString(16).padStart(2, '0');
const volumeColor = (c) => faded(+c.close >= +c.open ? 'up' : 'down', 0.35);
const options = () => ({
    layout: { background: { color: token('surface') }, textColor: token('text-muted'), fontFamily: "'IBM Plex Mono', monospace", fontSize: 11 },
    grid: { vertLines: { color: token('border') }, horzLines: { color: token('border') } },
    crosshair: { vertLine: { color: token('text-subtle'), style: 2 }, horzLine: { color: token('text-subtle'), style: 2 } },
    rightPriceScale: { borderColor: token('border') },
    timeScale: { borderColor: token('border'), timeVisible: true },
    autoSize: true,
});

export function priceChart(initial, liveUrl) {
    return {
        charts: [],
        status: 'Waiting for the stream',
        candleSeries: null,
        volumeSeries: null,
        // The last closed candle drawn, the bar still forming as the server sent it, and what the zoom belongs to.
        lastClosed: 0,
        forming: null,
        key: null,
        count: 0,
        // What the live read asks about: the instrument, the timeframe and the last minute the chart was computed through.
        liveQuery: null,
        reading: false,
        init() {
            this.render(initial);
            this.$wire.$on('chart-data', (event) => this.render(event.payload ?? event[0]?.payload ?? event));
            // Apart from Livewire, so the read never travels with (and swallows) the operator's clicks (D-87).
            const timer = setInterval(() => this.poll(), 1000);
            document.addEventListener('livewire:navigating', () => clearInterval(timer), { once: true });
        },
        async poll() {
            if (this.reading || !this.liveQuery || !this.liveQuery.through || document.hidden) {
                return;
            }

            this.reading = true;

            try {
                const response = await fetch(`${liveUrl}?${new URLSearchParams(this.liveQuery)}`, { headers: { Accept: 'application/json' } });

                if (!response.ok) {
                    this.status = `Not live: the server answered ${response.status}`;

                    return;
                }

                const live = await response.json();

                if (live.closed) {
                    // A candle of this timeframe closed: the screen recomputes candles and layers.
                    await this.$wire.$refresh();

                    return;
                }

                this.status = live.status;
                this.forming = live.bar;
                this.drawForming();
            } catch {
                this.status = 'Not live: the server does not answer';
            } finally {
                this.reading = false;
            }
        },
        drawForming() {
            const raw = this.forming;

            if (!raw || !this.candleSeries || time(raw.time) <= this.lastClosed) {
                return;
            }

            this.candleSeries.update({ time: time(raw.time), open: +raw.open, high: +raw.high, low: +raw.low, close: +raw.close });
            this.volumeSeries.update({ time: time(raw.time), value: +raw.volume, color: volumeColor(raw) });
        },
        render(payload) {
            // The operator's zoom and scroll survive a new candle; another instrument, timeframe or size fits again.
            const keep = this.charts.length > 0 && payload && this.key === payload.key;
            const range = keep ? this.charts[0].timeScale().getVisibleLogicalRange() : null;
            const previousCount = this.count;

            this.charts.forEach((chart) => chart.remove());
            this.charts = [];
            this.candleSeries = null;
            this.volumeSeries = null;
            this.$refs.panels.innerHTML = '';
            this.$refs.profile.innerHTML = '';
            this.key = payload?.key ?? null;
            this.count = payload?.candles?.length ?? 0;
            this.liveQuery = payload?.live ?? null;

            if (!keep) {
                this.forming = null;
            }

            if (!payload || !payload.candles || payload.candles.length === 0) {
                return;
            }

            const main = createChart(this.$refs.main, options());
            this.charts.push(main);
            const candles = main.addSeries(CandlestickSeries, {
                upColor: token('up'), downColor: token('down'), wickUpColor: token('up'), wickDownColor: token('down'), borderVisible: false,
            });
            candles.setData(payload.candles.map((c) => ({ time: time(c.time), open: +c.open, high: +c.high, low: +c.low, close: +c.close })));
            const volume = main.addSeries(HistogramSeries, { priceScaleId: 'volume', priceFormat: { type: 'volume' } });
            main.priceScale('volume').applyOptions({ scaleMargins: { top: 0.82, bottom: 0 } });
            volume.setData(payload.candles.map((c) => ({ time: time(c.time), value: +c.volume, color: volumeColor(c) })));
            this.candleSeries = candles;
            this.volumeSeries = volume;
            this.lastClosed = time(payload.candles[payload.candles.length - 1].time);

            const overlaySlots = ['series-1', 'series-3', 'series-4'];
            const panelSlots = ['series-1', 'series-2', 'series-3', 'series-4', 'series-5'];
            const markers = [];
            const panels = {};
            let overlays = 0;

            for (const [expression, values] of Object.entries(payload.series)) {
                const type = payload.types[expression] ?? '';
                const points = values.map((v, i) => (v === null ? null : { time: time(payload.candles[i].time), value: v })).filter(Boolean);

                if (type === 'a condition') {
                    points.filter((p) => p.value === 'true').forEach((p) => markers.push({ time: p.time, position: 'belowBar', shape: 'arrowUp', color: token('text'), text: expression }));
                    continue;
                }

                const numeric = points.map((p) => ({ time: p.time, value: +p.value }));

                if (type === 'a price series') {
                    const color = overlays < overlaySlots.length ? token(overlaySlots[overlays]) : token('text-subtle');
                    main.addSeries(LineSeries, { color, lineWidth: 2, title: overlays < overlaySlots.length ? expression : `Other: ${expression}`, priceLineVisible: false }).setData(numeric);
                    overlays++;
                    continue;
                }

                // One panel per kind of measure: one y-axis per chart (A5).
                if (!panels[type]) {
                    const holder = document.createElement('div');
                    holder.className = 'h-[160px] w-full';
                    holder.setAttribute('aria-label', type);
                    this.$refs.panels.appendChild(holder);
                    panels[type] = { chart: createChart(holder, options()), count: 0 };
                    this.charts.push(panels[type].chart);
                }

                const panel = panels[type];
                panel.chart.addSeries(LineSeries, { color: token(panelSlots[panel.count % panelSlots.length]), lineWidth: 2, title: expression, priceLineVisible: false }).setData(numeric);
                panel.count++;
            }

            if (markers.length > 0) {
                createSeriesMarkers(candles, markers.sort((a, b) => a.time - b.time));
            }

            this.drawForming();

            if (range) {
                // Following the right edge when it was in view: the window moves on by the candles that closed.
                const shift = range.to >= previousCount - 1 ? this.count - previousCount : 0;
                this.charts.forEach((chart) => chart.timeScale().setVisibleLogicalRange({ from: range.from + shift, to: range.to + shift }));
            } else {
                this.charts.forEach((chart) => chart.timeScale().fitContent());
            }

            // The price scale has coordinates only once the chart has laid out.
            requestAnimationFrame(() => requestAnimationFrame(() => this.drawProfile(candles, payload.profile ?? [])));
            main.timeScale().subscribeVisibleTimeRangeChange(() => this.drawProfile(candles, payload.profile ?? []));
        },
        drawProfile(series, bins) {
            const box = this.$refs.profile;
            box.innerHTML = '';
            const largest = Math.max(...bins.map((b) => +b.volume), 0);

            if (largest === 0) {
                return;
            }

            for (const bin of bins) {
                const top = series.priceToCoordinate(+bin.high);
                const bottom = series.priceToCoordinate(+bin.low);

                if (top === null || bottom === null) {
                    continue;
                }

                const bar = document.createElement('div');
                bar.style.cssText = `position:absolute;right:0;top:${top}px;height:${Math.max(1, bottom - top - 1)}px;width:${(100 * +bin.volume) / largest}%;background:${faded('series-3', 0.35)}`;
                box.appendChild(bar);
            }
        },
    };
}
