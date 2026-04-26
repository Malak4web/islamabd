import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useSectionStore = defineStore('section', () => {
    const sections = ref([])
    const isLoading = ref(false)

    async function fetchSections(pageId) {
        isLoading.value = true
        try {
            const { data } = await api.get(`/admin/sections/${pageId}`)
            sections.value = data.data
        } finally {
            isLoading.value = false
        }
    }

    async function updateSection(id, content) {
        isLoading.value = true
        try {
            const { data } = await api.put(`/admin/sections/${id}`, { content })
            const index = sections.value.findIndex(s => s.id === id)
            if (index !== -1) sections.value[index] = data.data
            return data.data
        } finally {
            isLoading.value = false
        }
    }

    async function toggleSection(id) {
        try {
            const { data } = await api.patch(`/admin/sections/${id}/toggle`)
            const index = sections.value.findIndex(s => s.id === id)
            if (index !== -1) sections.value[index] = data.data
        } catch (err) {
            console.error('Failed to toggle section', err)
        }
    }

    async function reorderSections(ids) {
        try {
            await api.patch('/admin/sections/reorder', { order: ids })
        } catch (err) {
            console.error('Failed to reorder sections', err)
        }
    }

    return {
        sections,
        isLoading,
        fetchSections,
        updateSection,
        toggleSection,
        reorderSections
    }
})
