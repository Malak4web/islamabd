import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AppFooter from '@/components/public/AppFooter.vue'
import { useSettingStore } from '@/stores/settingStore'
import { useServiceStore } from '@/stores/serviceStore'
import { createRouter, createWebHistory } from 'vue-router'
import i18n from '@/i18n'

const router = createRouter({
  history: createWebHistory(),
  routes: [{ path: '/', component: { template: '<div></div>' } }]
})

describe('AppFooter.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders phone and email from settings', async () => {
        const store = useSettingStore()
        store.settings = {
            phone_main: '+123456789',
            email_main: 'info@indesign.com',
            facebook: 'fb.com'
        }
        
        const serviceStore = useServiceStore()
        vi.spyOn(serviceStore, 'fetchServices').mockResolvedValue([])
        
        const wrapper = mount(AppFooter, {
            global: {
                plugins: [router, i18n]
            }
        })
        
        expect(wrapper.text()).toContain('+123456789')
        expect(wrapper.text()).toContain('info@indesign.com')
    })
})
