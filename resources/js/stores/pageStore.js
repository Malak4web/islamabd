import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const usePageStore = defineStore('page', () => {
    const pages = ref([])
    const currentPage = ref(null)
    const isLoading = ref(false)

    async function fetchPages() {
        isLoading.value = true
        try {
            const { data } = await api.get('/admin/pages')
            pages.value = data.data
        } finally {
            isLoading.value = false
        }
    }

    async function fetchPage(slug) {
        isLoading.value = true
        try {
            const { data } = await api.get(`/v1/pages/${slug}`)
            currentPage.value = data.data
            return data.data
        } finally {
            isLoading.value = false
        }
    }

    async function updatePageSEO(id, seoData) {
        isLoading.value = true
        try {
            const { data } = await api.put(`/admin/pages/${id}`, seoData)
            // Update local pages list
            const index = pages.value.findIndex(p => p.id === id)
            if (index !== -1) pages.value[index] = data.data
            return data.data
        } finally {
            isLoading.value = false
        }
    }

    return {
        pages,
        currentPage,
        isLoading,
        fetchPages,
        fetchPage,
        updatePageSEO
    }
})
