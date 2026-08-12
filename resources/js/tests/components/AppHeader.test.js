import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AppHeader from '@/components/public/AppHeader.vue'
import { useSettingStore } from '@/stores/settingStore'
import { createRouter, createWebHistory } from 'vue-router'
import i18n from '@/i18n'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'Home', component: { template: '<div></div>' } },
    { path: '/contact', name: 'Contact', component: { template: '<div></div>' } }
  ]
})

describe('AppHeader.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders nav links for all pages', async () => {
        const wrapper = mount(AppHeader, {
            global: {
                plugins: [router, i18n],
                stubs: {
                    LanguageSwitcher: true,
                    MobileMenuToggle: true
                }
            }
        })
        
        expect(wrapper.text()).toContain('ESLAM ABDULGHANI DESIGNS')
        // Check for desktop nav links (Home, About, etc. are translated)
        // Since we use NavLinks component, let's just check if it exists
        expect(wrapper.findComponent({ name: 'NavLinks' }).exists()).toBe(true)
    })

    it('header becomes scrolled after scroll event', async () => {
        const wrapper = mount(AppHeader, {
            global: {
                plugins: [router, i18n],
                stubs: ['LanguageSwitcher', 'MobileMenuToggle']
            }
        })
        
        // Initial state
        expect(wrapper.classes()).not.toContain('bg-black/90')
        
        // Mock scroll
        window.scrollY = 100
        window.dispatchEvent(new Event('scroll'))
        await flushPromises()
        
        expect(wrapper.vm.isScrolled).toBe(true)
    })
})
