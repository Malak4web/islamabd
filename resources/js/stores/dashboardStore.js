import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useDashboardStore = defineStore('dashboard', () => {
    const stats = ref({
        new_contacts_count: 0,
        total_projects: 0,
        active_services: 0,
        media_count: 0,
        recent_contacts: []
    })
    const loading = ref(false)

    const fetchStats = async () => {
        loading.value = true
        try {
            const { data } = await api.get('/admin/dashboard/stats')
            stats.value = data.data
        } catch (error) {
            console.error('Failed to fetch dashboard stats', error)
        } finally {
            loading.value = false
        }
    }

    return {
        stats,
        loading,
        fetchStats
    }
})
