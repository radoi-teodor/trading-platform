// The chart of docs/brand.md, A5: candles in up/down, volume at reduced opacity, price layers over the candles in
// series slots 1, 3 and 4 (2 and 5 sit too close to `down`), other layers in panels below, conditions as markers,
// the window's volume profile as bars on the right. Colors come from the tokens, never from literals.
import { createChart, CandlestickSeries, HistogramSeries, LineSeries, createSeriesMarkers } from 'lightweight-charts';

const token = (name) => getComputedStyle(document.documentElement).getPropertyValue(`--${name}`).trim();
const time = (iso) => Math.floor(Date.parse(iso) / 1000);
const options = () => ({
    layout: { background: { color: token('surface') }, textColor: token('text-muted'), fontFamily: "'IBM Plex Mono', monospace", fontSize: 11 },
    grid: { vertLines: { color: token('border') }, horzLines: { color: token('border') } },
    crosshair: { vertLine: { color: token('text-subtle'), style: 2 }, horzLine: { color: token('text-subtle'), style: 2 } },
    rightPriceScale: { borderColor: token('border') },
    timeScale: { borderColor: token('border'), timeVisible: true },
    autoSize: true,
});

export function priceChart(initial) {
    return {
        charts: [],
        init() {
            this.render(initial);
            this.$wire.$on('chart-data', (event) => this.render(event.payload ?? event[0]?.payload ?? event));
        },
        render(payload) {
            this.charts.forEach((chart) => chart.remove());
            this.charts = [];
            this.$refs.panels.innerHTML = '';
            this.$refs.profile.innerHTML = '';

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
            volume.setData(payload.candles.map((c) => ({ time: time(c.time), value: +c.volume, color: `color-mix(in srgb, ${+c.close >= +c.open ? token('up') : token('down')} 35%, transparent)` })));

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

            main.timeScale().fitContent();
            this.charts.slice(1).forEach((chart) => chart.timeScale().fitContent());
            this.drawProfile(candles, payload.profile ?? []);
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
                bar.style.cssText = `position:absolute;right:0;top:${top}px;height:${Math.max(1, bottom - top - 1)}px;width:${(100 * +bin.volume) / largest}%;background:color-mix(in srgb, ${token('series-3')} 25%, transparent)`;
                box.appendChild(bar);
            }
        },
    };
}
