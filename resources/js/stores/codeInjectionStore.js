import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useCodeInjectionStore = defineStore('codeInjection', () => {
    const injections = ref([])
    const isLoading = ref(false)

    async function fetchAdminInjections() {
        isLoading.value = true
        try {
            const { data } = await api.get('/admin/code-injections')
            injections.value = data.data
        } finally {
            isLoading.value = false
        }
    }

    async function createInjection(payload) {
        const { data } = await api.post('/admin/code-injections', payload)
        injections.value.unshift(data.data)
        return data.data
    }

    async function updateInjection(id, payload) {
        const { data } = await api.put(`/admin/code-injections/${id}`, payload)
        const index = injections.value.findIndex(i => i.id === id)
        if (index !== -1) injections.value[index] = data.data
        return data.data
    }

    async function toggleInjection(id) {
        const { data } = await api.patch(`/admin/code-injections/${id}/toggle`)
        const index = injections.value.findIndex(i => i.id === id)
        if (index !== -1) injections.value[index].is_active = data.data.is_active
    }

    async function deleteInjection(id) {
        await api.delete(`/admin/code-injections/${id}`)
        injections.value = injections.value.filter(i => i.id !== id)
    }

    return {
        injections,
        isLoading,
        fetchAdminInjections,
        createInjection,
        updateInjection,
        toggleInjection,
        deleteInjection
    }
})
