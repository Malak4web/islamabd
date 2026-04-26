import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useMediaStore = defineStore('media', () => {
    const items = ref([])
    const isLoading = ref(false)
    const uploadProgress = ref(0)

    const fetchMedia = async () => {
        isLoading.value = true
        try {
            const { data } = await api.get('/admin/media')
            items.value = data.data
        } catch (error) {
            console.error('Failed to fetch media', error)
        } finally {
            isLoading.value = false
        }
    }

    const uploadMedia = async (files) => {
        const formData = new FormData()
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i])
        }

        try {
            const { data } = await api.post('/admin/media', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
                onUploadProgress: (progressEvent) => {
                    uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                }
            })
            // Prepend new items
            items.value = [...data.data, ...items.value]
            uploadProgress.value = 0
            return data.data
        } catch (error) {
            uploadProgress.value = 0
            throw error
        }
    }

    const deleteMedia = async (id) => {
        try {
            await api.delete(`/admin/media/${id}`)
            items.value = items.value.filter(item => item.id !== id)
        } catch (error) {
            throw error
        }
    }

    return {
        items,
        isLoading,
        uploadProgress,
        fetchMedia,
        uploadMedia,
        deleteMedia
    }
})
