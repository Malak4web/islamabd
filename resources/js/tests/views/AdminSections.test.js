import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import AdminSections from '@/views/admin/AdminSections.vue'
import { usePageStore } from '@/stores/pageStore'
import { useSectionStore } from '@/stores/sectionStore'

vi.mock('@/api/axios', () => ({
    default: {
        get: vi.fn((url) => {
            if (url.includes('/admin/pages')) return Promise.resolve({ data: { data: [{ id: 1, title_en: 'Home' }] } })
            if (url.includes('/admin/sections/')) return Promise.resolve({ data: { data: [{ id: 1, key: 'hero', is_active: true, content: { title: 'Test' } }] } })
            return Promise.resolve({ data: { data: [] } })
        }),
        put: vi.fn(() => Promise.resolve({ data: { data: {} } })),
        patch: vi.fn(() => Promise.resolve({ data: { data: {} } })),
        post: vi.fn(() => Promise.resolve({ data: { data: {} } }))
    }
}))


// Mock route
vi.mock('vue-router', () => ({
    useRoute: () => ({ params: { id: 1 } })
}))

describe('AdminSections.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.stubGlobal('alert', vi.fn())
    })

    it('renders SEO panel correctly', async () => {
        const wrapper = mount(AdminSections)
        await flushPromises()
        expect(wrapper.text()).toContain('SEO & Meta')
        expect(wrapper.find('input[type="text"]').exists()).toBe(true)
    })

    it('renders section cards when sections are present', async () => {
        const wrapper = mount(AdminSections, {
            global: {
                stubs: {
                    'router-link': true,
                    'draggable': {
                        template: '<div><slot v-for="item in modelValue" :element="item" name="item"></slot></div>',
                        props: ['modelValue']
                    }
                }
            }
        })
        await flushPromises()
        
        expect(wrapper.text()).toContain('hero')
    })

    it('opens edit modal on click', async () => {
        const wrapper = mount(AdminSections, {
            global: {
                stubs: {
                    'router-link': true,
                    'draggable': {
                        template: '<div><slot v-for="item in modelValue" :element="item" name="item"></slot></div>',
                        props: ['modelValue']
                    }
                }
            }
        })
        await flushPromises()
        
        await wrapper.find('[data-edit-section]').trigger('click')
        await flushPromises()
        
        expect(wrapper.text()).toContain('Edit Section: hero')
        expect(wrapper.find('[data-content-textarea]').element.value).toBe('Test')

    })
})

