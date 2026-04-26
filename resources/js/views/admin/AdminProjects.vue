<template>
  <div class="space-y-10">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Portfolio</h1>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Manage your architectural design milestones</p>
      </div>
      <button 
        @click="openAddModal"
        class="flex items-center gap-3 px-8 py-4 bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:scale-105 active:scale-95 transition-all shadow-xl shadow-amber-500/10"
      >
        <Plus class="w-4 h-4" />
        New Project
      </button>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-6 p-6 bg-slate-900 border border-slate-800 rounded-[2rem]">
       <div class="flex items-center gap-4">
          <div class="relative group">
             <Filter class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 group-hover:text-amber-500 transition-colors" />
             <select 
                v-model="categoryFilter" 
                class="bg-slate-950 border border-slate-800 pl-12 pr-8 py-3 rounded-xl outline-none focus:border-amber-500 transition-all text-xs font-bold uppercase tracking-widest text-slate-400 appearance-none"
             >
                <option value="all">All Categories</option>
                <option value="residential">Residential</option>
                <option value="commercial">Commercial</option>
                <option value="hospitality">Hospitality</option>
                <option value="landscape">Landscape</option>
                <option value="retail">Retail</option>
             </select>
          </div>
          
          <div class="relative group">
             <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 group-hover:text-amber-500 transition-colors" />
             <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Search projects..."
                class="bg-slate-950 border border-slate-800 pl-12 pr-8 py-3 rounded-xl outline-none focus:border-amber-500 transition-all text-xs font-bold text-white min-w-[300px]"
             >
          </div>
       </div>

       <div class="flex items-center gap-2 p-1 bg-slate-950 rounded-xl border border-slate-800">
          <button 
            @click="viewMode = 'grid'" 
            class="p-3 rounded-lg transition-all"
            :class="viewMode === 'grid' ? 'bg-amber-500 text-slate-950 shadow-lg' : 'text-slate-500 hover:text-white'"
          >
             <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            @click="viewMode = 'list'" 
            class="p-3 rounded-lg transition-all"
            :class="viewMode === 'list' ? 'bg-amber-500 text-slate-950 shadow-lg' : 'text-slate-500 hover:text-white'"
          >
             <List class="w-4 h-4" />
          </button>
       </div>
    </div>

    <!-- Grid View -->
    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
       <div v-for="project in filteredProjects" :key="project.id" class="group bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden hover:border-amber-500/30 transition-all shadow-xl flex flex-col h-full">
          <div class="relative aspect-[4/3] overflow-hidden bg-slate-950">
             <img v-if="project.cover_image" :src="project.cover_image" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-60 group-hover:opacity-100" />
             <div v-else class="w-full h-full flex items-center justify-center text-slate-800">
                <ImageIcon class="w-12 h-12" />
             </div>
             
             <!-- Badges -->
             <div class="absolute top-6 left-6 flex flex-col gap-2">
                <span v-if="project.is_featured" class="px-3 py-1 bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-widest rounded-lg flex items-center gap-1.5 shadow-lg">
                   <Star class="w-3 h-3 fill-current" /> Featured
                </span>
                <span class="px-3 py-1 bg-slate-950/80 backdrop-blur-sm text-white text-[10px] font-black uppercase tracking-widest rounded-lg border border-white/10">
                   {{ project.category }}
                </span>
             </div>

             <!-- Overlay Actions -->
             <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                <button @click="editProject(project)" class="w-12 h-12 bg-white text-slate-950 rounded-2xl flex items-center justify-center hover:bg-amber-500 transition-colors shadow-2xl">
                   <Edit3 class="w-5 h-5" />
                </button>
                <button @click="openDeleteConfirm(project)" class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center hover:bg-red-600 transition-colors shadow-2xl">
                   <Trash2 class="w-5 h-5" />
                </button>
             </div>
          </div>
          
          <div class="p-8 space-y-4 flex-1 flex flex-col">
             <div class="space-y-1 flex-1">
                <h3 class="text-base font-bold text-white uppercase tracking-tight line-clamp-1">{{ project.title_en }}</h3>
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ project.title_ar }}</p>
             </div>
             
             <div class="flex items-center justify-between pt-4 border-t border-slate-800/50">
                <button 
                  @click="toggleProject(project)"
                  data-test-toggle
                  class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-colors"
                  :class="project.is_active ? 'text-emerald-500' : 'text-slate-600'"
                >
                   <div class="w-1.5 h-1.5 rounded-full" :class="project.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-700'"></div>
                   {{ project.is_active ? 'Public' : 'Hidden' }}
                </button>
                <button @click="toggleFeatured(project)" class="text-slate-600 hover:text-amber-500 transition-colors">
                   <Star class="w-4 h-4" :class="{ 'fill-current text-amber-500': project.is_featured }" />
                </button>
             </div>
          </div>
       </div>
    </div>

    <!-- List View -->
    <div v-else class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
       <table class="w-full text-left">
          <thead>
             <tr class="bg-slate-950/30 border-b border-slate-800">
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Project</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Category</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Featured</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
             </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/50">
             <tr v-for="project in filteredProjects" :key="project.id" class="hover:bg-white/[0.02] transition-colors group">
                <td class="px-8 py-6 flex items-center gap-6">
                   <div class="w-16 h-10 bg-slate-950 rounded-lg overflow-hidden border border-slate-800 shrink-0">
                      <img v-if="project.cover_image" :src="project.cover_image" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-all">
                   </div>
                   <div class="space-y-0.5">
                      <p class="text-sm font-bold text-white group-hover:text-amber-500 transition-colors">{{ project.title_en }}</p>
                      <p class="text-[10px] font-bold text-slate-600">{{ project.title_ar }}</p>
                   </div>
                </td>
                <td class="px-8 py-6">
                   <span class="px-3 py-1 bg-slate-800 text-[10px] font-bold text-slate-400 rounded-lg border border-slate-700 uppercase tracking-widest">
                      {{ project.category }}
                   </span>
                </td>
                <td class="px-8 py-6 text-center">
                   <button @click="toggleProject(project)" class="mx-auto flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border transition-all" :class="project.is_active ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-500' : 'border-slate-800 bg-slate-800/50 text-slate-500'">
                      {{ project.is_active ? 'Active' : 'Draft' }}
                   </button>
                </td>
                <td class="px-8 py-6 text-center">
                   <button @click="toggleFeatured(project)" class="mx-auto text-slate-700 hover:text-amber-500 transition-colors">
                      <Star class="w-5 h-5" :class="{ 'fill-current text-amber-500': project.is_featured }" />
                   </button>
                </td>
                <td class="px-8 py-6 text-right space-x-2">
                   <button @click="editProject(project)" class="w-8 h-8 inline-flex items-center justify-center bg-slate-950 text-slate-500 hover:text-amber-500 rounded-lg border border-slate-800 transition-all">
                      <Edit3 class="w-4 h-4" />
                   </button>
                   <button @click="openDeleteConfirm(project)" class="w-8 h-8 inline-flex items-center justify-center bg-slate-950 text-slate-500 hover:text-red-500 rounded-lg border border-slate-800 transition-all">
                      <Trash2 class="w-4 h-4" />
                   </button>
                </td>
             </tr>
          </tbody>
       </table>
    </div>

    <!-- Modals -->
    <ProjectFormModal 
      :is-open="showModal"
      :is-editing="isEditing"
      :form="form"
      :loading="projectStore.isLoading"
      @close="showModal = false"
      @save="saveProject"
      @upload-cover="handleCoverUpload"
      @upload-gallery="handleGalleryUpload"
      @remove-image="handleRemoveImage"
    />

    <ConfirmModal 
      :is-open="showDeleteConfirm"
      title="Delete Portfolio Entry"
      :message="`Confirm deletion of '${projectToDelete?.title_en}'? All associated gallery media will be detached.`"
      @cancel="showDeleteConfirm = false"
      @confirm="deleteProject"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed, watch } from 'vue'
import { useProjectStore } from '@/stores/projectStore'
import { 
  Plus, 
  Search, 
  Filter, 
  LayoutGrid, 
  List, 
  Star, 
  Edit3, 
  Trash2, 
  Image as ImageIcon,
  Upload
} from 'lucide-vue-next'
import ProjectFormModal from '@/components/admin/ProjectFormModal.vue'
import ConfirmModal from '@/components/admin/ConfirmModal.vue'

const projectStore = useProjectStore()
const viewMode = ref('grid')
const categoryFilter = ref('all')
const searchQuery = ref('')
const showModal = ref(false)
const showDeleteConfirm = ref(false)
const isEditing = ref(false)
const editingId = ref(null)
const projectToDelete = ref(null)

const form = reactive({
    title_en: '',
    title_ar: '',
    category: 'residential',
    description_en: '',
    description_ar: '',
    cover_image: null,
    gallery: [],
    is_featured: false,
    is_active: true
})

onMounted(async () => {
    await projectStore.fetchAdminProjects()
})

const filteredProjects = computed(() => {
    return projectStore.projects.filter(p => {
        const matchesCategory = categoryFilter.value === 'all' || p.category === categoryFilter.value
        const matchesSearch = (p.title_en?.toLowerCase() || '').includes(searchQuery.value.toLowerCase()) || 
                             (p.title_ar?.toLowerCase() || '').includes(searchQuery.value.toLowerCase())
        return matchesCategory && matchesSearch
    })
})

const openAddModal = () => {
    isEditing.value = false
    editingId.value = null
    Object.assign(form, {
        title_en: '',
        title_ar: '',
        category: 'residential',
        description_en: '',
        description_ar: '',
        cover_image: null,
        gallery: [],
        is_featured: false,
        is_active: true
    })
    showModal.value = true
}

const editProject = (project) => {
    isEditing.value = true
    editingId.value = project.id
    Object.assign(form, { ...project })
    showModal.value = true
}

const saveProject = async () => {
    try {
        if (isEditing.value) {
            await projectStore.updateProject(editingId.value, { ...form })
        } else {
            await projectStore.createProject({ ...form })
        }
        showModal.value = false
    } catch (err) {
        console.error(err)
    }
}

const toggleProject = async (project) => {
    await projectStore.toggleProject(project.id)
}

const toggleFeatured = async (project) => {
    await projectStore.featureProject(project.id)
}

const handleCoverUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return
    try {
        const url = await projectStore.uploadCover(editingId.value, file)
        form.cover_image = url
    } catch (err) {
        console.error(err)
    }
}

const handleGalleryUpload = async (event) => {
    const files = event.target.files
    if (!files.length) return
    try {
        const newGallery = await projectStore.uploadGallery(editingId.value, files)
        form.gallery = newGallery
    } catch (err) {
        console.error(err)
    }
}

const handleRemoveImage = async (imagePath) => {
    try {
        const newGallery = await projectStore.removeGalleryImage(editingId.value, imagePath)
        form.gallery = newGallery
    } catch (err) {
        console.error(err)
    }
}

const openDeleteConfirm = (project) => {
    projectToDelete.value = project
    showDeleteConfirm.value = true
}

const deleteProject = async () => {
    if (!projectToDelete.value) return
    await projectStore.deleteProject(projectToDelete.value.id)
    showDeleteConfirm.value = false
    projectToDelete.value = null
}
</script>
