import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import ContactView from '@/views/public/ContactView.vue'
import { useSettingStore } from '@/stores/settingStore'
import { usePageStore } from '@/stores/pageStore'
import i18n from '@/i18n'

describe('ContactView.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders contact info from settings', async () => {
        const store = useSettingStore()
        store.settings = {
            address: '123 Design St',
            phone_main: '+123456789',
            email_main: 'info@indesign.com'
        }
        
        const pageStore = usePageStore()
        vi.spyOn(pageStore, 'fetchPage').mockResolvedValue({})
        
        const wrapper = mount(ContactView, {
            global: {
                plugins: [i18n],
                stubs: ['ContactForm']
            }
        })
        
        expect(wrapper.text()).toContain('123 Design St')
        expect(wrapper.text()).toContain('+123456789')
        expect(wrapper.text()).toContain('info@indesign.com')
    })

    it('renders contact form component', async () => {
        const pageStore = usePageStore()
        vi.spyOn(pageStore, 'fetchPage').mockResolvedValue({})
        
        const wrapper = mount(ContactView, {
            global: {
                plugins: [i18n],
                stubs: {
                    ContactForm: { template: '<div class="contact-form-stub"></div>' }
                }
            }
        })
        
        expect(wrapper.find('.contact-form-stub').exists()).toBe(true)
    })
})
