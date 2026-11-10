// Trading X front end: Livewire brings Alpine; the chart and the DSL editor register as Alpine components.
import { priceChart } from './chart';
import { dslEditor } from './editor';
import { instrumentPicker } from './picker';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('priceChart', priceChart);
    window.Alpine.data('dslEditor', dslEditor);
    window.Alpine.data('instrumentPicker', instrumentPicker);
});
