import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminDashboard from '@/views/admin/AdminDashboard.vue'
import { useDashboardStore } from '@/stores/dashboardStore'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [{ path: '/admin', component: { template: '<div></div>' } }]
})

describe('AdminDashboard.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders 4 stat cards', async () => {
        const store = useDashboardStore()
        vi.spyOn(store, 'fetchStats').mockResolvedValue({})
        
        const wrapper = mount(AdminDashboard, {
            global: {
                plugins: [router],
                stubs: ['StatCard']
            }
        })
        
        expect(wrapper.findAll('stat-card-stub').length).toBe(4)
    })

    it('fetches dashboard stats on mount', async () => {
        const store = useDashboardStore()
        vi.spyOn(store, 'fetchStats').mockResolvedValue({})
        
        mount(AdminDashboard, {
            global: {
                plugins: [router],
                stubs: ['StatCard']
            }
        })
        
        expect(store.fetchStats).toHaveBeenCalled()
    })

    it('recent contacts table renders rows', async () => {
        const store = useDashboardStore()
        store.stats.recent_contacts = [
            { id: 1, name: 'John Doe', phone: '123', service: 'Design', created_at: '2023-01-01', status: 'new' }
        ]
        vi.spyOn(store, 'fetchStats').mockResolvedValue({})
        
        const wrapper = mount(AdminDashboard, {
            global: {
                plugins: [router],
                stubs: ['StatCard']
            }
        })
        
        expect(wrapper.text()).toContain('John Doe')
        expect(wrapper.text()).toContain('Design')
    })
})
