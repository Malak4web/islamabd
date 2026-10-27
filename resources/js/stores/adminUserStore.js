import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useAdminUserStore = defineStore('adminUser', () => {
    const admins = ref([])
    const isLoading = ref(false)
    const error = ref(null)

    async function fetchAdmins() {
        isLoading.value = true
        error.value = null
        try {
            const { data } = await api.get('/admin/admins')
            admins.value = data.data || []
            return admins.value
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch team members'
            throw err
        } finally {
            isLoading.value = false
        }
    }

    async function createAdmin(payload) {
        isLoading.value = true
        error.value = null
        try {
            const { data } = await api.post('/admin/admins', payload)
            if (data.data) {
                admins.value.push(data.data)
            }
            return data.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to create team member'
            throw err
        } finally {
            isLoading.value = false
        }
    }

    async function updateAdmin(id, payload) {
        isLoading.value = true
        error.value = null
        try {
            const { data } = await api.put(`/admin/admins/${id}`, payload)
            const idx = admins.value.findIndex(a => a.id === id)
            if (idx !== -1 && data.data) {
                admins.value[idx] = data.data
            }
            return data.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to update team member'
            throw err
        } finally {
            isLoading.value = false
        }
    }

    async function deleteAdmin(id) {
        isLoading.value = true
        error.value = null
        try {
            await api.delete(`/admin/admins/${id}`)
            admins.value = admins.value.filter(a => a.id !== id)
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to delete team member'
            throw err
        } finally {
            isLoading.value = false
        }
    }

    return {
        admins,
        isLoading,
        error,
        fetchAdmins,
        createAdmin,
        updateAdmin,
        deleteAdmin
    }
})
