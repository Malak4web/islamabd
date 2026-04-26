import { vi } from 'vitest';

vi.mock('monaco-editor-vue3', () => {
    return {
        VueMonacoEditor: {
            template: '<textarea data-monaco-editor></textarea>'
        }
    };
});
