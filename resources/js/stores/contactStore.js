import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useContactStore = defineStore('contact', () => {
    const contacts = ref([])
    const pagination = ref({
        total: 0,
        per_page: 15,
        current_page: 1,
        last_page: 1
    })
    const isLoading = ref(false)
    const formErrors = ref({})

    async function submitContact(data) {
        isLoading.value = true
        formErrors.value = {}
        try {
            await api.post('/v1/contacts', data)
            return true
        } catch (error) {
            if (error.response?.status === 422) {
                formErrors.value = error.response.data.errors
            }
            throw error
        } finally {
            isLoading.value = false
        }
    }

    async function fetchAdminContacts(filters = {}) {
        isLoading.value = true
        try {
            const { data } = await api.get('/admin/contacts', { params: filters })
            contacts.value = data.data
            pagination.value = data.meta
        } finally {
            isLoading.value = false
        }
    }

    async function fetchContact(id) {
        isLoading.value = true
        try {
            const { data } = await api.get(`/admin/contacts/${id}`)
            return data.data
        } finally {
            isLoading.value = false
        }
    }

    async function markAsRead(id) {
        const { data } = await api.patch(`/admin/contacts/${id}/read`)
        const index = contacts.value.findIndex(c => c.id === id)
        if (index !== -1) contacts.value[index] = data.data
    }

    async function markAsReplied(id) {
        const { data } = await api.patch(`/admin/contacts/${id}/replied`)
        const index = contacts.value.findIndex(c => c.id === id)
        if (index !== -1) contacts.value[index] = data.data
    }

    async function deleteContact(id) {
        await api.delete(`/admin/contacts/${id}`)
        contacts.value = contacts.value.filter(c => c.id !== id)
    }

    async function bulkDelete(ids) {
        await api.delete('/admin/contacts/bulk', {
            data: { ids }
        })
        contacts.value = contacts.value.filter(c => !ids.includes(c.id))
    }

    return {
        contacts,
        pagination,
        isLoading,
        formErrors,
        submitContact,
        fetchAdminContacts,
        fetchContact,
        markAsRead,
        markAsReplied,
        deleteContact,
        bulkDelete
    }
})
