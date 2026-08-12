<template>
  <div class="space-y-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-[#111111] uppercase tracking-tighter">{{ $t('admin.media_library') }}</h1>
        <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">{{ $t('admin.media_manage') }}</p>
      </div>
    </div>

    <!-- Upload Zone -->
    <div 
      class="relative group border-2 border-dashed border-[#E0DACE] hover:border-[#C5A880]/50 rounded-[3rem] p-16 text-center transition-all bg-[#FFFFFF] shadow-sm overflow-hidden"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="handleDrop"
    >
      <div v-if="isDragging" class="absolute inset-0 bg-[#C5A880]/15 backdrop-blur-sm z-10 pointer-events-none flex items-center justify-center">
         <p class="text-xl font-black text-[#C5A880] uppercase animate-bounce">{{ $t('admin.drop_upload') }}</p>
      </div>

      <div class="relative z-0 space-y-6">
         <div class="w-24 h-24 mx-auto bg-[#F0ECE1] rounded-3xl flex items-center justify-center text-[#555555] group-hover:text-[#C5A880] transition-colors shadow-inner">
            <UploadCloud class="w-10 h-10" />
         </div>
         <div class="space-y-2">
            <h3 class="text-lg font-bold text-[#111111] uppercase tracking-tight">{{ $t('admin.drop_or_browse') }}</h3>
            <p class="text-[10px] text-[#555555] uppercase tracking-widest font-black">{{ $t('admin.media_formats_hint') }}</p>
         </div>
         <input type="file" multiple class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileSelect">
         
         <!-- Progress Bar -->
         <div v-if="mediaStore.uploadProgress > 0" class="max-w-xs mx-auto pt-8">
            <div class="h-1.5 w-full bg-[#E0DACE] rounded-full overflow-hidden">
               <div 
                 class="h-full bg-[#C5A880] transition-all duration-300 shadow-sm"
                 :style="{ width: `${mediaStore.uploadProgress}%` }"
               ></div>
            </div>
            <p class="mt-2 text-[10px] font-black text-[#C5A880] uppercase tracking-widest">{{ $t('admin.uploading') }} {{ mediaStore.uploadProgress }}%</p>
         </div>
      </div>
    </div>

    <!-- Media Grid -->
    <div class="space-y-8">
       <div class="flex items-center justify-between px-4">
          <h3 class="text-[10px] font-black text-[#555555] uppercase tracking-[0.4em]">{{ $t('admin.asset_collection') }}</h3>
          <div class="flex items-center gap-4">
             <span class="text-[10px] font-bold text-[#555555] uppercase">{{ mediaStore.items.length }} {{ $t('admin.files') }}</span>
          </div>
       </div>

       <div v-if="mediaStore.isLoading && !mediaStore.items.length" class="p-40 text-center">
          <div class="w-16 h-16 border-4 border-[#C5A880]/20 border-t-[#C5A880] rounded-full animate-spin mx-auto"></div>
       </div>

       <div v-else-if="!mediaStore.items.length" class="p-40 text-center space-y-4">
          <div class="w-20 h-20 mx-auto bg-[#F0ECE1] rounded-3xl flex items-center justify-center text-[#C5A880] border border-[#E0DACE]">
             <ImageIcon class="w-10 h-10" />
          </div>
          <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.4em]">{{ $t('admin.library_empty') }}</p>
       </div>

       <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">
          <div v-for="item in mediaStore.items" :key="item.id" class="group relative aspect-square bg-[#FFFFFF] border border-[#E0DACE] rounded-[2rem] overflow-hidden hover:border-[#C5A880]/50 transition-all shadow-md">
             <img :src="item.url" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-80 group-hover:opacity-100" />
             
             <!-- Overlay -->
             <div class="absolute inset-0 bg-[#111111]/60 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center gap-4 p-4">
                <p class="text-[10px] font-bold text-white truncate w-full text-center mb-2 px-2 uppercase tracking-tighter">{{ item.filename }}</p>
                <div class="flex gap-2">
                   <button @click="copyUrl(item.url)" class="w-10 h-10 bg-white text-[#111111] rounded-xl flex items-center justify-center hover:bg-[#C5A880] hover:text-white transition-colors shadow-2xl" :title="$t('admin.copied_clipboard')">
                      <Copy class="w-4 h-4" />
                   </button>
                   <button @click="confirmDelete(item)" class="w-10 h-10 bg-red-500 text-white rounded-xl flex items-center justify-center hover:bg-red-600 transition-colors shadow-2xl" :title="$t('admin.delete')">
                      <Trash2 class="w-4 h-4" />
                   </button>
                </div>
             </div>
          </div>
       </div>
    </div>

    <!-- Confirm Modal -->
    <ConfirmModal 
      :is-open="showDeleteConfirm"
      :title="$t('admin.discard_asset')"
      :message="$t('admin.delete_asset_confirm')"
      @cancel="showDeleteConfirm = false"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useMediaStore } from '@/stores/mediaStore'
import { useToast } from '@/composables/useToast'
import { useI18n } from 'vue-i18n'
import { 
  UploadCloud, 
  Image as ImageIcon, 
  Trash2, 
  Copy, 
  Search,
  CheckCircle
} from 'lucide-vue-next'
import ConfirmModal from '@/components/admin/ConfirmModal.vue'

const mediaStore = useMediaStore()
const { toast } = useToast()
const { t } = useI18n()
const isDragging = ref(false)
const showDeleteConfirm = ref(false)
const itemToDelete = ref(null)

onMounted(() => {
    mediaStore.fetchMedia()
})

const handleFileSelect = (event) => {
    const files = event.target.files
    if (files.length > 0) uploadFiles(files)
}

const handleDrop = (event) => {
    isDragging.value = false
    const files = event.dataTransfer.files
    if (files.length > 0) uploadFiles(files)
}

const uploadFiles = async (files) => {
    try {
        await mediaStore.uploadMedia(files)
        toast.success(t('admin.upload_success') + ` (${files.length})`)
    } catch (err) {
        toast.error(t('admin.upload_failed'))
    }
}

const copyUrl = (url) => {
    navigator.clipboard.writeText(url)
    toast.info(t('admin.asset_url_copied'))
}

const confirmDelete = (item) => {
    itemToDelete.value = item
    showDeleteConfirm.value = true
}

const handleDelete = async () => {
    if (!itemToDelete.value) return
    try {
        await mediaStore.deleteMedia(itemToDelete.value.id)
        toast.success(t('admin.asset_removed'))
        showDeleteConfirm.value = false
        itemToDelete.value = null
    } catch (err) {
        toast.error(t('admin.asset_remove_failed'))
    }
}
</script>
