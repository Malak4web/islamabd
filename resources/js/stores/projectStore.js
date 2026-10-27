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
        const map = new Map(projects.value.map(p => [p.id, p]))
        const ordered = []
        ids.forEach((id, index) => {
            const p = map.get(id)
            if (p) {
                p.order = index + 1
                ordered.push(p)
                map.delete(id)
            }
        })
        // append any remaining
        map.forEach(p => ordered.push(p))
        projects.value = ordered
    }

    async function uploadCover(id, file, onProgress = null) {
        const formData = new FormData()
        formData.append('file', file)
        const config = onProgress ? {
            onUploadProgress: (e) => {
                if (e.total) onProgress(Math.round((e.loaded * 100) / e.total))
            }
        } : undefined
        const { data } = config
            ? await api.post(`/admin/projects/${id}/cover`, formData, config)
            : await api.post(`/admin/projects/${id}/cover`, formData)
        const url = data.data.url || data.data.cover_image
        const index = projects.value.findIndex(p => p.id === id)
        if (index !== -1 && url) {
            projects.value[index].cover_image = url
        }
        return url
    }

    async function uploadGallery(id, files, onProgress = null) {
        const formData = new FormData()
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i])
        }
        const config = onProgress ? {
            onUploadProgress: (e) => {
                if (e.total) onProgress(Math.round((e.loaded * 100) / e.total))
            }
        } : undefined
        const { data } = config
            ? await api.post(`/admin/projects/${id}/gallery`, formData, config)
            : await api.post(`/admin/projects/${id}/gallery`, formData)
        const gallery = data.data.gallery
        const index = projects.value.findIndex(p => p.id === id)
        if (index !== -1 && gallery) {
            projects.value[index].gallery = gallery
        }
        return gallery
    }

    async function removeGalleryImage(id, imagePath) {
        const { data } = await api.delete(`/admin/projects/${id}/gallery`, {
            data: { image: imagePath }
        })
        const gallery = data.data.gallery
        const index = projects.value.findIndex(p => p.id === id)
        if (index !== -1 && gallery) {
            projects.value[index].gallery = gallery
        }
        return gallery
    }

    return {
        projects,
        featuredProjects,
        pagination,
        loading,
        // Alias, for the same reason as in serviceStore: consumers wrote
        // `projectStore.isLoading` and got `undefined`, so the skeleton state
        // was dead code on both the public grid and the admin table.
        isLoading: loading,
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
