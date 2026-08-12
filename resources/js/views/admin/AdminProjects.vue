<template>
  <div class="space-y-10">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-[#111111] uppercase tracking-tighter">{{ $t('admin.portfolio') }}</h1>
        <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">{{ $t('admin.portfolio_manage') }}</p>
      </div>
      <button 
        @click="openAddModal"
        class="flex items-center gap-3 px-8 py-4 bg-[#C5A880] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-[#111111] hover:scale-105 active:scale-95 transition-all shadow-xl shadow-[#C5A880]/10"
      >
        <Plus class="w-4 h-4" />
        {{ $t('admin.new_project') }}
      </button>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-6 p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-[2rem] shadow-sm">
       <div class="flex items-center gap-4">
          <div class="relative group">
             <Filter class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#555555] group-hover:text-[#C5A880] transition-colors" />
             <select 
                v-model="categoryFilter" 
                class="bg-[#F7F5F0] border border-[#E0DACE] pl-12 pr-8 py-3 rounded-xl outline-none focus:border-[#C5A880] transition-all text-xs font-bold uppercase tracking-widest text-[#555555] appearance-none"
             >
                <option value="all">{{ $t('admin.cat_all') }}</option>
                <option value="residential">{{ $t('admin.cat_residential') }}</option>
                <option value="commercial">{{ $t('admin.cat_commercial') }}</option>
                <option value="hospitality">{{ $t('admin.cat_hospitality') }}</option>
                <option value="landscape">{{ $t('admin.cat_landscape') }}</option>
                <option value="retail">{{ $t('admin.cat_retail') }}</option>
             </select>
          </div>
          
          <div class="relative group">
             <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#555555] group-hover:text-[#C5A880] transition-colors" />
             <input 
                v-model="searchQuery"
                type="text" 
                :placeholder="$t('admin.search_projects')"
                class="bg-[#F7F5F0] border border-[#E0DACE] pl-12 pr-8 py-3 rounded-xl outline-none focus:border-[#C5A880] transition-all text-xs font-bold text-[#111111] min-w-[300px]"
             >
          </div>
       </div>

       <div class="flex items-center gap-2 p-1 bg-[#F0ECE1] rounded-xl border border-[#E0DACE]">
          <button 
            @click="viewMode = 'grid'" 
            class="p-3 rounded-lg transition-all"
            :class="viewMode === 'grid' ? 'bg-[#C5A880] text-white shadow-xs' : 'text-[#555555] hover:text-[#111111]'"
          >
             <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            @click="viewMode = 'list'" 
            class="p-3 rounded-lg transition-all"
            :class="viewMode === 'list' ? 'bg-[#C5A880] text-white shadow-xs' : 'text-[#555555] hover:text-[#111111]'"
          >
             <List class="w-4 h-4" />
          </button>
       </div>
    </div>

    <!-- Grid View -->
    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
       <div v-for="project in filteredProjects" :key="project.id" class="group bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden hover:border-[#C5A880]/50 transition-all shadow-md flex flex-col h-full">
          <div class="relative aspect-[4/3] overflow-hidden bg-[#F7F5F0]">
             <img v-if="project.cover_image" :src="project.cover_image" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-80 group-hover:opacity-100" />
             <div v-else class="w-full h-full flex items-center justify-center text-[#555555]">
                <ImageIcon class="w-12 h-12" />
             </div>
             
             <!-- Badges -->
             <div class="absolute top-6 left-6 flex flex-col gap-2">
                <span v-if="project.is_featured" class="px-3 py-1 bg-[#C5A880] text-white text-[10px] font-black uppercase tracking-widest rounded-lg flex items-center gap-1.5 shadow-md">
                   <Star class="w-3 h-3 fill-current" /> {{ $t('admin.featured') }}
                </span>
                <span class="px-3 py-1 bg-[#111111]/70 backdrop-blur-sm text-white text-[10px] font-black uppercase tracking-widest rounded-lg border border-white/20">
                   {{ project.category }}
                </span>
             </div>

             <!-- Overlay Actions -->
             <div class="absolute inset-0 bg-[#111111]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                <button @click="editProject(project)" class="w-12 h-12 bg-white text-[#111111] rounded-2xl flex items-center justify-center hover:bg-[#C5A880] hover:text-white transition-colors shadow-2xl">
                   <Edit3 class="w-5 h-5" />
                </button>
                <button @click="openDeleteConfirm(project)" class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center hover:bg-red-600 transition-colors shadow-2xl">
                   <Trash2 class="w-5 h-5" />
                </button>
             </div>
          </div>
          
          <div class="p-8 space-y-4 flex-1 flex flex-col">
             <div class="space-y-1 flex-1 text-start">
                <h3 class="text-base font-bold text-[#111111] uppercase tracking-tight line-clamp-1">{{ project.title_en }}</h3>
                <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ project.title_ar }}</p>
             </div>
             
             <div class="flex items-center justify-between pt-4 border-t border-[#E0DACE]">
                <button 
                  @click="toggleProject(project)"
                  data-test-toggle
                  class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest transition-colors"
                  :class="project.is_active ? 'text-emerald-600' : 'text-[#555555]'"
                >
                   <div class="w-1.5 h-1.5 rounded-full" :class="project.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-[#555555]'"></div>
                   {{ project.is_active ? $t('admin.public') : $t('admin.hidden') }}
                </button>
                <button @click="toggleFeatured(project)" class="text-[#555555] hover:text-[#C5A880] transition-colors">
                   <Star class="w-4 h-4" :class="{ 'fill-current text-[#C5A880]': project.is_featured }" />
                </button>
             </div>
          </div>
       </div>
    </div>

    <!-- List View -->
    <div v-else class="bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden shadow-md">
       <table class="w-full text-start">
          <thead>
             <tr class="bg-[#F7F5F0] border-b border-[#E0DACE] text-start">
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-start">{{ $t('admin.project') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-start">{{ $t('admin.category') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-center">{{ $t('admin.tbl_status') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-center">{{ $t('admin.featured') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-end">{{ $t('admin.actions') }}</th>
             </tr>
          </thead>
          <tbody class="divide-y divide-[#E0DACE]">
             <tr v-for="project in filteredProjects" :key="project.id" class="hover:bg-[#F0ECE1]/50 transition-colors group">
                <td class="px-8 py-6 flex items-center gap-6 text-start">
                   <div class="w-16 h-10 bg-[#F7F5F0] rounded-lg overflow-hidden border border-[#E0DACE] shrink-0">
                      <img v-if="project.cover_image" :src="project.cover_image" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-all">
                   </div>
                   <div class="space-y-0.5">
                      <p class="text-sm font-bold text-[#111111] group-hover:text-[#C5A880] transition-colors">{{ project.title_en }}</p>
                      <p class="text-[10px] font-bold text-[#555555]">{{ project.title_ar }}</p>
                   </div>
                </td>
                <td class="px-8 py-6 text-start">
                   <span class="px-3 py-1 bg-[#F0ECE1] text-[10px] font-bold text-[#555555] rounded-lg border border-[#E0DACE] uppercase tracking-widest">
                      {{ project.category }}
                   </span>
                </td>
                <td class="px-8 py-6 text-center">
                   <button @click="toggleProject(project)" class="mx-auto flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border transition-all" :class="project.is_active ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-600' : 'border-[#E0DACE] bg-[#F0ECE1] text-[#555555]'">
                      {{ project.is_active ? $t('admin.active') : $t('admin.draft') }}
                   </button>
                </td>
                <td class="px-8 py-6 text-center">
                   <button @click="toggleFeatured(project)" class="mx-auto text-[#555555] hover:text-[#C5A880] transition-colors">
                      <Star class="w-5 h-5" :class="{ 'fill-current text-[#C5A880]': project.is_featured }" />
                   </button>
                </td>
                <td class="px-8 py-6 text-end space-x-2 rtl:space-x-reverse">
                   <button @click="editProject(project)" class="w-8 h-8 inline-flex items-center justify-center bg-[#F7F5F0] text-[#555555] hover:text-[#C5A880] hover:border-[#C5A880]/30 rounded-lg border border-[#E0DACE] transition-all">
                      <Edit3 class="w-4 h-4" />
                   </button>
                   <button @click="openDeleteConfirm(project)" class="w-8 h-8 inline-flex items-center justify-center bg-[#F7F5F0] text-[#555555] hover:text-red-500 rounded-lg border border-[#E0DACE] transition-all">
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
      :title="$t('admin.delete_portfolio_entry')"
      :message="`${$t('admin.delete_project_confirm')} (${projectToDelete?.title_en})`"
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
