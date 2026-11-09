// The DSL editor (docs/brand.md, A7): monospace, line numbers, YAML highlighting. Livewire compiles what is typed
// and lists errors with their line and column; the editor keeps the text in sync with the component.
import { EditorView, basicSetup } from 'codemirror';
import { yaml } from '@codemirror/lang-yaml';

export function dslEditor(property) {
    return {
        view: null,
        init() {
            const theme = EditorView.theme({
                '&': { backgroundColor: 'var(--surface-2)', color: 'var(--text)', fontSize: '13px', minHeight: '360px' },
                '.cm-content': { fontFamily: "'IBM Plex Mono', monospace", caretColor: 'var(--text)' },
                '.cm-gutters': { backgroundColor: 'var(--surface)', color: 'var(--text-subtle)', borderRight: '1px solid var(--border)' },
                '.cm-activeLine, .cm-activeLineGutter': { backgroundColor: 'color-mix(in srgb, var(--accent) 8%, transparent)' },
                '&.cm-focused': { outline: '1px solid var(--focus)' },
            }, { dark: true });
            this.view = new EditorView({
                doc: this.$wire.get(property) ?? '',
                extensions: [basicSetup, yaml(), theme, EditorView.updateListener.of((update) => {
                    if (update.docChanged) {
                        clearTimeout(this.timer);
                        this.timer = setTimeout(() => this.$wire.set(property, this.view.state.doc.toString()), 400);
                    }
                })],
                parent: this.$refs.editor,
            });
            this.$wire.$on('document-loaded', () => {
                const text = this.$wire.get(property) ?? '';
                this.view.dispatch({ changes: { from: 0, to: this.view.state.doc.length, insert: text } });
            });
        },
    };
}
