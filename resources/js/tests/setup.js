import { vi, beforeEach } from 'vitest';
import { config } from '@vue/test-utils';
import { createPinia, setActivePinia } from 'pinia';

// Mock monaco editor
vi.mock('monaco-editor-vue3', () => {
    return {
        VueMonacoEditor: {
            template: '<textarea data-monaco-editor></textarea>'
        }
    };
});

// Mock i18n translation function globally using actual English translations
import enTranslations from '../i18n/en.json';

config.global.mocks = {
    $t: (key) => {
        const parts = key.split('.');
        let result = enTranslations;
        for (const part of parts) {
            if (result && typeof result === 'object' && part in result) {
                result = result[part];
            } else {
                return key;
            }
        }
        return result;
    }
};

// Initialize Pinia globally before each test
beforeEach(() => {
    setActivePinia(createPinia());
});
