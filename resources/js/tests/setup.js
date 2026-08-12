import { vi, beforeEach } from 'vitest';
import { config } from '@vue/test-utils';
import { createPinia, setActivePinia } from 'pinia';
import enTranslations from '../i18n/en.json';
import reveal from '../directives/reveal.js';
import depth from '../directives/depth.js';
import tilt from '../directives/tilt.js';

// These three are registered on the app in app.js, which tests never boot. All
// of them are no-ops in jsdom — no IntersectionObserver, no matchMedia — but
// they have to resolve or every component that uses one mounts with a warning.
config.global.directives = { reveal, depth, tilt };

// Mock monaco editor
vi.mock('monaco-editor-vue3', () => {
    return {
        VueMonacoEditor: {
            template: '<textarea data-monaco-editor></textarea>'
        }
    };
});

// Mock vue-i18n useI18n
vi.mock('vue-i18n', async (importOriginal) => {
    const actual = await importOriginal();
    return {
        ...actual,
        useI18n: () => ({
            t: (key) => {
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
        })
    };
});

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
