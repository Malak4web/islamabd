import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api/axios'

export const useSettingStore = defineStore('setting', () => {
    const settings = ref({})
    const isLoading = ref(false)

    // Helper to get a setting value reactively
    const get = (key, fallback = '') => {
        return computed(() => settings.value[key] ?? fallback)
    }

    async function fetchSettings() {
        isLoading.value = true
        try {
            const { data } = await api.get('/v1/settings')
            settings.value = data.data
        } finally {
            isLoading.value = false
        }
    }

    async function fetchAdminSettings(group = null) {
        isLoading.value = true
        try {
            const params = group ? { group } : {}
            const { data } = await api.get('/admin/settings', { params })
            return data.data
        } finally {
            isLoading.value = false
        }
    }

    async function updateSetting(key, value) {
        isLoading.value = true
        try {
            const { data } = await api.put(`/admin/settings/${key}`, { value })
            settings.value[key] = value // Optimistic update
            return data
        } finally {
            isLoading.value = false
        }
    }

    async function bulkUpdate(settingsArray) {
        isLoading.value = true
        try {
            await api.post('/admin/settings/bulk', { settings: settingsArray })
            // Refresh flat settings
            await fetchSettings()
        } finally {
            isLoading.value = false
        }
    }

    async function uploadImage(key, file) {
        isLoading.value = true
        try {
            const form = new FormData()
            form.append('file', file)
            const { data } = await api.post(`/admin/settings/image/${key}`, form, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            settings.value[key] = data.data.url
            return data.data.url
        } finally {
            isLoading.value = false
        }
    }

    return {
        settings,
        isLoading,
        get,
        fetchSettings,
        fetchAdminSettings,
        updateSetting,
        bulkUpdate,
        uploadImage
    }
})
