import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useServiceStore = defineStore('service', () => {
    const services = ref([])
    const currentService = ref(null)
    const loading = ref(false)

    async function fetchServices() {
        loading.value = true
        try {
            const { data } = await api.get('/v1/services')
            services.value = data.data
        } finally {
            loading.value = false
        }
    }

    async function fetchService(id) {
        loading.value = true
        try {
            const { data } = await api.get(`/v1/services/${id}`)
            currentService.value = data.data
        } finally {
            loading.value = false
        }
    }

    async function fetchAdminServices() {
        loading.value = true
        try {
            const { data } = await api.get('/admin/services')
            services.value = data.data
        } finally {
            loading.value = false
        }
    }

    async function createService(data) {
        loading.value = true
        try {
            const response = await api.post('/admin/services', data)
            services.value.push(response.data.data)
            return response.data.data
        } finally {
            loading.value = false
        }
    }

    async function updateService(id, data) {
        loading.value = true
        try {
            const response = await api.put(`/admin/services/${id}`, data)
            const index = services.value.findIndex(s => s.id === id)
            if (index !== -1) services.value[index] = response.data.data
            return response.data.data
        } finally {
            loading.value = false
        }
    }

    async function deleteService(id) {
        try {
            await api.delete(`/admin/services/${id}`)
            services.value = services.value.filter(s => s.id !== id)
        } catch (err) {
            console.error('Failed to delete service', err)
            throw err
        }
    }

    async function toggleService(id) {
        try {
            const { data } = await api.patch(`/admin/services/${id}/toggle`)
            const index = services.value.findIndex(s => s.id === id)
            if (index !== -1) services.value[index] = data.data
        } catch (err) {
            console.error('Failed to toggle service', err)
        }
    }

    async function reorderServices(ids) {
        try {
            await api.patch('/admin/services/reorder', { order: ids })
        } catch (err) {
            console.error('Failed to reorder services', err)
        }
    }

    async function uploadImage(id, file) {
        const formData = new FormData()
        formData.append('file', file)
        const { data } = await api.post(`/admin/services/${id}/image`, formData)
        return data.data.url
    }

    async function uploadIcon(id, file) {
        const formData = new FormData()
        formData.append('file', file)
        const { data } = await api.post(`/admin/services/${id}/icon`, formData)
        return data.data.url
    }

    return {
        services,
        currentService,
        loading,
        // Every other store in the app names this `isLoading`, so call sites
        // reached for that name here too — and silently got `undefined`, which
        // meant the loading skeletons never rendered. Both names now resolve.
        isLoading: loading,
        fetchServices,
        fetchService,
        fetchAdminServices,
        createService,
        updateService,
        deleteService,
        toggleService,
        reorderServices,
        uploadImage,
        uploadIcon
    }
})
