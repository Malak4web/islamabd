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

    <!-- Reorder hint when active -->
    <div class="flex items-center justify-between px-6 py-3 bg-[#F7F5F0] border border-[#E0DACE] rounded-2xl text-xs font-bold text-[#555555]">
      <div class="flex items-center gap-2">
        <GripVertical class="w-4 h-4 text-[#C5A880]" />
        <span>{{ $t('admin.drag_reorder') || 'Drag items by handle to reorder projects (reflects on website)' }}</span>
      </div>
      <span v-if="isFiltered" class="text-[10px] uppercase tracking-wider text-[#C5A880] font-black">
        {{ $t('admin.filter_active_clear_to_reorder') || 'Filter active: clear filter to reorder all' }}
      </span>
    </div>

    <!-- Grid View -->
    <div v-if="viewMode === 'grid'">
      <draggable 
        v-model="displayProjects" 
        item-key="id" 
        @end="handleReorder"
        handle=".drag-handle"
        :disabled="isFiltered"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8"
      >
        <template #item="{ element: project }">
          <div class="group bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden hover:border-[#C5A880]/50 transition-all shadow-md flex flex-col h-full relative">
            <div class="relative aspect-[4/3] overflow-hidden bg-[#F7F5F0]">
              <img 
                :src="getProjectCover(project)" 
                @error="onImageError($event, project)"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-90 group-hover:opacity-100" 
              />
              
              <!-- Drag Handle Badge -->
              <div 
                v-if="!isFiltered"
                class="drag-handle absolute top-4 right-4 z-10 cursor-grab active:cursor-grabbing p-2.5 bg-white/90 backdrop-blur-md text-[#111111] hover:text-[#C5A880] rounded-xl shadow-lg border border-black/5 opacity-80 hover:opacity-100 transition-all"
                :title="$t('admin.drag_reorder') || 'Drag to reorder'"
              >
                <GripVertical class="w-4 h-4" />
              </div>

              <!-- Badges -->
              <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                <span v-if="project.is_featured" class="px-3 py-1 bg-[#C5A880] text-white text-[10px] font-black uppercase tracking-widest rounded-lg flex items-center gap-1.5 shadow-md">
                  <Star class="w-3 h-3 fill-current" /> {{ $t('admin.featured') }}
                </span>
                <span class="px-3 py-1 bg-[#111111]/70 backdrop-blur-sm text-white text-[10px] font-black uppercase tracking-widest rounded-lg border border-white/20">
                  {{ project.category }}
                </span>
              </div>

              <!-- Overlay Actions -->
              <div class="absolute inset-0 bg-[#111111]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4 z-10">
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
                <div class="flex items-center justify-between gap-2">
                  <h3 class="text-base font-bold text-[#111111] uppercase tracking-tight line-clamp-1">{{ project.title_en }}</h3>
                  <span class="text-xs font-mono font-bold text-[#C5A880]">#{{ project.order }}</span>
                </div>
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
        </template>
      </draggable>
    </div>

    <!-- List View -->
    <div v-else class="bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden shadow-md">
      <table class="w-full text-start">
        <thead>
          <tr class="bg-[#F7F5F0] border-b border-[#E0DACE] text-start">
            <th class="w-12 px-4 py-5 text-center text-[10px] font-black text-[#555555]"></th>
            <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-start">{{ $t('admin.project') }}</th>
            <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-start">{{ $t('admin.category') }}</th>
            <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-center">{{ $t('admin.display_order') || 'Order' }}</th>
            <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-center">{{ $t('admin.tbl_status') }}</th>
            <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-center">{{ $t('admin.featured') }}</th>
            <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest text-end">{{ $t('admin.actions') }}</th>
          </tr>
        </thead>
        <draggable 
          v-model="displayProjects" 
          item-key="id" 
          @end="handleReorder"
          handle=".drag-handle"
          :disabled="isFiltered"
          tag="tbody"
          class="divide-y divide-[#E0DACE]"
        >
          <template #item="{ element: project }">
            <tr class="hover:bg-[#F0ECE1]/50 transition-colors group">
              <td class="px-4 py-6 text-center w-12">
                <div 
                  v-if="!isFiltered"
                  class="drag-handle cursor-grab active:cursor-grabbing p-2 text-[#555555] group-hover:text-[#C5A880] transition-colors bg-[#F7F5F0] rounded-xl border border-transparent group-hover:border-[#C5A880]/30 inline-flex"
                  :title="$t('admin.drag_reorder') || 'Drag to reorder'"
                >
                  <GripVertical class="w-4 h-4" />
                </div>
              </td>
              <td class="px-8 py-6 flex items-center gap-6 text-start">
                <div class="w-16 h-12 bg-[#F7F5F0] rounded-lg overflow-hidden border border-[#E0DACE] shrink-0">
                  <img 
                    :src="getProjectCover(project)" 
                    @error="onImageError($event, project)"
                    class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-all"
                  >
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
                <span class="text-xs font-mono font-bold text-[#C5A880]">#{{ project.order }}</span>
              </td>
              <td class="px-8 py-6 text-center">
                <button @click="toggleProject(project)" data-test-toggle class="mx-auto flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border transition-all" :class="project.is_active ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-600' : 'border-[#E0DACE] bg-[#F0ECE1] text-[#555555]'">
                  <div class="w-1.5 h-1.5 rounded-full" :class="project.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-[#555555]'"></div>
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
          </template>
        </draggable>
      </table>
    </div>

    <!-- Modals -->
    <ProjectFormModal 
      :is-open="showModal"
      :is-editing="isEditing"
      :form="form"
      :loading="projectStore.isLoading"
      :cover-upload-progress="coverUploadProgress"
      :gallery-upload-progress="galleryUploadProgress"
      @close="showModal = false"
      @save="saveProject"
      @upload-cover="handleCoverUpload"
      @upload-gallery="handleGalleryUpload"
      @remove-image="handleRemoveImage"
      @clear-cover="handleClearCover"
      @reorder-gallery="handleGalleryReorder"
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
import draggable from 'vuedraggable'
import { useProjectStore } from '@/stores/projectStore'
import { useToast } from '@/composables/useToast'
import { useI18n } from 'vue-i18n'
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
  Upload,
  GripVertical
} from 'lucide-vue-next'
import ProjectFormModal from '@/components/admin/ProjectFormModal.vue'
import ConfirmModal from '@/components/admin/ConfirmModal.vue'

const projectStore = useProjectStore()
const { t } = useI18n()
const { toast } = useToast()

const viewMode = ref('grid')
const categoryFilter = ref('all')
const searchQuery = ref('')
const showModal = ref(false)
const showDeleteConfirm = ref(false)
const isEditing = ref(false)
const editingId = ref(null)
const projectToDelete = ref(null)

const localProjects = ref([])
const pendingCoverFile = ref(null)
const pendingGalleryFiles = ref([])
const coverUploadProgress = ref(0)
const galleryUploadProgress = ref(0)

const CATEGORY_FALLBACKS = {
  residential: '/images/projects/residential-1-1600.jpg',
  commercial: '/images/projects/commercial-1-1600.jpg',
  hospitality: '/images/projects/hospitality-1-1600.jpg',
  landscape: '/images/projects/landscape-1-1600.jpg',
  retail: '/images/projects/retail-1-1600.jpg',
  exterior: '/images/projects/exterior-1-1600.jpg',
  industrial: '/images/projects/industrial-1-1600.jpg'
}

const getProjectCover = (project) => {
  if (!project) return '/images/defaults/hero_fallback.jpg'
  const cover = project.cover_image
  if (cover && !cover.includes('eslamabdulghanidesigns.com')) {
    return cover
  }
  const cat = project.category || 'residential'
  return CATEGORY_FALLBACKS[cat] || '/images/defaults/hero_fallback.jpg'
}

const onImageError = (event, project) => {
  const cat = project?.category || 'residential'
  const fallback = CATEGORY_FALLBACKS[cat] || '/images/defaults/hero_fallback.jpg'
  if (event.target.src !== fallback) {
    event.target.src = fallback
  }
}

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
  localProjects.value = [...projectStore.projects]
})

watch(() => projectStore.projects, (newVal) => {
  localProjects.value = [...newVal]
}, { deep: true, immediate: true })

const isFiltered = computed(() => categoryFilter.value !== 'all' || !!searchQuery.value.trim())

const filteredProjects = computed(() => {
  return localProjects.value.filter(p => {
    const matchesCategory = categoryFilter.value === 'all' || p.category === categoryFilter.value
    const matchesSearch = (p.title_en?.toLowerCase() || '').includes(searchQuery.value.toLowerCase()) || 
                         (p.title_ar?.toLowerCase() || '').includes(searchQuery.value.toLowerCase())
    return matchesCategory && matchesSearch
  })
})

const displayProjects = computed({
  get() {
    return isFiltered.value ? filteredProjects.value : localProjects.value
  },
  set(val) {
    if (!isFiltered.value) {
      localProjects.value = val
    }
  }
})

const handleReorder = async () => {
  if (isFiltered.value) return
  const ids = localProjects.value.map(p => p.id)
  localProjects.value.forEach((p, idx) => {
    p.order = idx + 1
  })
  try {
    await projectStore.reorderProjects(ids)
    toast.success(t('admin.reorder_success') || 'Projects reordered successfully!')
  } catch (err) {
    console.error(err)
    toast.error('Failed to save project order')
  }
}

const openAddModal = () => {
  isEditing.value = false
  editingId.value = null
  pendingCoverFile.value = null
  pendingGalleryFiles.value = []
  coverUploadProgress.value = 0
  galleryUploadProgress.value = 0
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
  pendingCoverFile.value = null
  pendingGalleryFiles.value = []
  coverUploadProgress.value = 0
  galleryUploadProgress.value = 0
  Object.assign(form, { 
    ...project,
    gallery: Array.isArray(project.gallery) ? [...project.gallery] : []
  })
  showModal.value = true
}

const saveProject = async () => {
  try {
    if (isEditing.value) {
      await projectStore.updateProject(editingId.value, { ...form })
      toast.success(t('admin.project_updated') || 'Project updated successfully')
    } else {
      const created = await projectStore.createProject({ ...form })
      if (created && created.id) {
        if (pendingCoverFile.value) {
          coverUploadProgress.value = 10
          await projectStore.uploadCover(created.id, pendingCoverFile.value, (p) => {
            coverUploadProgress.value = p
          })
        }
        if (pendingGalleryFiles.value.length) {
          galleryUploadProgress.value = 10
          await projectStore.uploadGallery(created.id, pendingGalleryFiles.value, (p) => {
            galleryUploadProgress.value = p
          })
        }
        await projectStore.fetchAdminProjects()
      }
      toast.success(t('admin.project_created') || 'Project created successfully')
    }
    coverUploadProgress.value = 0
    galleryUploadProgress.value = 0
    showModal.value = false
  } catch (err) {
    console.error(err)
    coverUploadProgress.value = 0
    galleryUploadProgress.value = 0
    toast.error(err?.response?.data?.message || 'Failed to save project')
  }
}

const toggleProject = async (project) => {
  await projectStore.toggleProject(project.id)
}

const toggleFeatured = async (project) => {
  await projectStore.featureProject(project.id)
}

const handleGalleryReorder = async () => {
  if (isEditing.value && editingId.value) {
    try {
      await projectStore.updateProject(editingId.value, { gallery: form.gallery })
      toast.success(t('admin.reorder_success') || 'Gallery order updated successfully')
    } catch (err) {
      console.error(err)
      toast.error('Failed to save gallery order')
    }
  }
}

const handleCoverUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return
  if (isEditing.value && editingId.value) {
    coverUploadProgress.value = 5
    try {
      const url = await projectStore.uploadCover(editingId.value, file, (progress) => {
        coverUploadProgress.value = progress
      })
      form.cover_image = url
      toast.success(t('admin.cover_uploaded') || 'Cover image updated successfully')
      setTimeout(() => { coverUploadProgress.value = 0 }, 1200)
    } catch (err) {
      console.error(err)
      coverUploadProgress.value = 0
      toast.error(err?.response?.data?.message || 'Failed to upload cover image')
    }
  } else {
    pendingCoverFile.value = file
    form.cover_image = URL.createObjectURL(file)
  }
  event.target.value = ''
}

const handleGalleryUpload = async (event) => {
  const files = Array.from(event.target.files || [])
  if (!files.length) return
  if (isEditing.value && editingId.value) {
    galleryUploadProgress.value = 5
    try {
      const newGallery = await projectStore.uploadGallery(editingId.value, files, (progress) => {
        galleryUploadProgress.value = progress
      })
      form.gallery = newGallery
      toast.success(t('admin.gallery_uploaded') || 'Gallery images uploaded successfully')
      setTimeout(() => { galleryUploadProgress.value = 0 }, 1200)
    } catch (err) {
      console.error(err)
      galleryUploadProgress.value = 0
      toast.error(err?.response?.data?.message || 'Failed to upload gallery images')
    }
  } else {
    files.forEach(f => {
      pendingGalleryFiles.value.push(f)
      form.gallery.push(URL.createObjectURL(f))
    })
  }
  event.target.value = ''
}

const handleRemoveImage = async (imagePath) => {
  if (isEditing.value && editingId.value) {
    try {
      const newGallery = await projectStore.removeGalleryImage(editingId.value, imagePath)
      form.gallery = newGallery
      toast.success('Image removed from gallery')
    } catch (err) {
      console.error(err)
      toast.error('Failed to remove image')
    }
  } else {
    const idx = form.gallery.indexOf(imagePath)
    if (idx !== -1) {
      form.gallery.splice(idx, 1)
      pendingGalleryFiles.value.splice(idx, 1)
    }
  }
}

const handleClearCover = () => {
  form.cover_image = null
  pendingCoverFile.value = null
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
