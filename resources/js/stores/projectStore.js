import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api/axios'

export const useProjectStore = defineStore('project', () => {
    const projects = ref([])
    const featuredProjects = computed(() => projects.value.filter(p => p.is_featured))
    const pagination = ref({
        total: 0,
        per_page: 9,
        current_page: 1,
        last_page: 1
    })
    const loading = ref(false)
    const currentProject = ref(null)

    async function fetchProjects(filters = {}) {
        loading.value = true
        try {
            const { data } = await api.get('/v1/projects', { params: filters })
            projects.value = data.data
            pagination.value = data.meta
        } finally {
            loading.value = false
        }
    }

    async function fetchProject(id) {
        loading.value = true
        try {
            const { data } = await api.get(`/v1/projects/${id}`)
            currentProject.value = data.data
        } finally {
            loading.value = false
        }
    }

    async function fetchAdminProjects() {
        loading.value = true
        try {
            const { data } = await api.get('/admin/projects')
            projects.value = data.data
        } finally {
            loading.value = false
        }
    }

    async function createProject(data) {
        loading.value = true
        try {
            const response = await api.post('/admin/projects', data)
            projects.value.push(response.data.data)
            return response.data.data
        } finally {
            loading.value = false
        }
    }

    async function updateProject(id, data) {
        loading.value = true
        try {
            const response = await api.put(`/admin/projects/${id}`, data)
            const index = projects.value.findIndex(p => p.id === id)
            if (index !== -1) projects.value[index] = response.data.data
            return response.data.data
        } finally {
            loading.value = false
        }
    }

    async function deleteProject(id) {
        await api.delete(`/admin/projects/${id}`)
        projects.value = projects.value.filter(p => p.id !== id)
    }

    async function toggleProject(id) {
        const { data } = await api.patch(`/admin/projects/${id}/toggle`)
        const index = projects.value.findIndex(p => p.id === id)
        if (index !== -1) projects.value[index] = data.data
    }

    async function featureProject(id) {
        const { data } = await api.patch(`/admin/projects/${id}/feature`)
        const index = projects.value.findIndex(p => p.id === id)
        if (index !== -1) projects.value[index] = data.data
    }

    async function reorderProjects(ids) {
        await api.patch('/admin/projects/reorder', { order: ids })
    }

    async function uploadCover(id, file) {
        const formData = new FormData()
        formData.append('file', file)
        const { data } = await api.post(`/admin/projects/${id}/cover`, formData)
        return data.data.url
    }

    async function uploadGallery(id, files) {
        const formData = new FormData()
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i])
        }
        const { data } = await api.post(`/admin/projects/${id}/gallery`, formData)
        return data.data.gallery
    }

    async function removeGalleryImage(id, imagePath) {
        const { data } = await api.delete(`/admin/projects/${id}/gallery`, {
            data: { image: imagePath }
        })
        return data.data.gallery
    }

    return {
        projects,
        featuredProjects,
        pagination,
        loading,
        currentProject,
        fetchProjects,
        fetchProject,
        fetchAdminProjects,
        createProject,
        updateProject,
        deleteProject,
        toggleProject,
        featureProject,
        reorderProjects,
        uploadCover,
        uploadGallery,
        removeGalleryImage
    }
})
