// The chart's instrument search: a text field over a native list of every instrument. Typing narrows the list and
// asks nothing of the server; the chart changes when the field holds a whole symbol, picked from the list or
// typed, or on Enter, which takes the first instrument that matches. Browsers do not agree on how a pick from the
// list is announced, so the text is what counts. What is not an instrument is never kept: leaving the field puts
// the current one back.
export function instrumentPicker(symbol) {
    return {
        current: symbol,

        find(text, loose) {
            const wanted = text.trim().toUpperCase();

            if (wanted === '') {
                return null;
            }

            const symbols = [...this.$refs.list.options].map((option) => option.value);
            const exact = symbols.find((value) => value.toUpperCase() === wanted);

            if (exact !== undefined || !loose) {
                return exact ?? null;
            }

            return symbols.find((value) => value.toUpperCase().startsWith(wanted)) ?? symbols.find((value) => value.toUpperCase().includes(wanted)) ?? null;
        },

        choose(loose) {
            const found = this.find(this.$refs.field.value, loose);

            if (found === null) {
                return;
            }

            this.$refs.field.value = found;

            if (found !== this.current) {
                this.current = found;
                this.$wire.set('symbol', found);
            }
        },

        restore() {
            this.$refs.field.value = this.current;
        },
    };
}
