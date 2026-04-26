import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import HomeView from '@/views/public/HomeView.vue'
import { usePageStore } from '@/stores/pageStore'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [{ path: '/', component: { template: '<div></div>' } }]
})

describe('HomeView.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('fetches home page on mount', async () => {
        const store = usePageStore()
        vi.spyOn(store, 'fetchPage').mockResolvedValue({})
        
        mount(HomeView, {
            global: {
                plugins: [router],
                stubs: ['HeroSlider', 'AboutSnippet', 'ServicesPreview', 'ProjectsPreview', 'CtaBanner']
            }
        })
        
        expect(store.fetchPage).toHaveBeenCalledWith('home')
    })

    it('renders sections when data is loaded', async () => {
        const store = usePageStore()
        vi.spyOn(store, 'fetchPage').mockResolvedValue({})
        
        store.currentPage = {
            title: 'Home',
            sections: [
                { id: 1, key: 'about_intro', content: {} },
                { id: 2, key: 'services_overview', content: {} }
            ]
        }
        store.loading = false
        
        const wrapper = mount(HomeView, {
            global: {
                plugins: [router],
                stubs: {
                   HeroSlider: true,
                   AboutSnippet: { template: '<div class="about-stub"></div>' },
                   ServicesPreview: { template: '<div class="services-stub"></div>' },
                   ProjectsPreview: true,
                   CtaBanner: true
                }
            }
        })
        
        await flushPromises()
        expect(wrapper.find('.about-stub').exists()).toBe(true)
        expect(wrapper.find('.services-stub').exists()).toBe(true)
    })
})
