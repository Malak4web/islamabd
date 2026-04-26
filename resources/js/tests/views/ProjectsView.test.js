import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import ProjectsView from '@/views/public/ProjectsView.vue'
import { useProjectStore } from '@/stores/projectStore'
import { usePageStore } from '@/stores/pageStore'
import { createRouter, createWebHistory } from 'vue-router'
import i18n from '@/i18n'

const router = createRouter({
  history: createWebHistory(),
  routes: [{ path: '/projects', component: { template: '<div></div>' } }]
})

describe('ProjectsView.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders category filter tabs', async () => {
        const store = useProjectStore()
        vi.spyOn(store, 'fetchProjects').mockResolvedValue([])
        
        const pageStore = usePageStore()
        vi.spyOn(pageStore, 'fetchPage').mockResolvedValue({})
        
        const wrapper = mount(ProjectsView, {
            global: {
                plugins: [router, i18n],
                stubs: {
                   ProjectCard: true,
                   CtaBanner: true
                }
            }
        })
        
        expect(wrapper.findComponent({ name: 'CategoryFilter' }).exists()).toBe(true)
    })

    it('shows loading state while fetching', async () => {
        const store = useProjectStore()
        store.isLoading = true
        vi.spyOn(store, 'fetchProjects').mockImplementation(() => new Promise(() => {}))
        
        const pageStore = usePageStore()
        vi.spyOn(pageStore, 'fetchPage').mockResolvedValue({})
        
        const wrapper = mount(ProjectsView, {
            global: {
                plugins: [router, i18n],
                stubs: {
                   ProjectCard: true,
                   CtaBanner: true
                }
            }
        })
        
        expect(wrapper.find('.animate-pulse').exists()).toBe(true)
    })

    it('shows empty state when no projects', async () => {
        const store = useProjectStore()
        store.isLoading = false
        store.projects = []
        vi.spyOn(store, 'fetchProjects').mockResolvedValue([])
        
        const pageStore = usePageStore()
        vi.spyOn(pageStore, 'fetchPage').mockResolvedValue({})
        
        const wrapper = mount(ProjectsView, {
            global: {
                plugins: [router, i18n],
                stubs: {
                   ProjectCard: true,
                   CtaBanner: true
                }
            }
        })
        
        await flushPromises()
        expect(wrapper.text()).toContain('No projects found')
    })
})
