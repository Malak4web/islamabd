<template>
  <div class="space-y-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Media Library</h1>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Manage your high-fidelity architectural assets</p>
      </div>
    </div>

    <!-- Upload Zone -->
    <div 
      class="relative group border-2 border-dashed border-slate-800 hover:border-amber-500/50 rounded-[3rem] p-16 text-center transition-all bg-slate-900 shadow-2xl overflow-hidden"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="handleDrop"
    >
      <div v-if="isDragging" class="absolute inset-0 bg-amber-500/10 backdrop-blur-sm z-10 pointer-events-none flex items-center justify-center">
         <p class="text-xl font-black text-amber-500 uppercase animate-bounce">Drop to Upload</p>
      </div>

      <div class="relative z-0 space-y-6">
         <div class="w-24 h-24 mx-auto bg-slate-950 rounded-3xl flex items-center justify-center text-slate-700 group-hover:text-amber-500 transition-colors shadow-inner">
            <UploadCloud class="w-10 h-10" />
         </div>
         <div class="space-y-2">
            <h3 class="text-lg font-bold text-white uppercase tracking-tight">Drop images here or click to browse</h3>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-black">Supports JPG, PNG, WEBP, SVG (Max 5MB)</p>
         </div>
         <input type="file" multiple class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileSelect">
         
         <!-- Progress Bar -->
         <div v-if="mediaStore.uploadProgress > 0" class="max-w-xs mx-auto pt-8">
            <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
               <div 
                 class="h-full bg-amber-500 transition-all duration-300 shadow-[0_0_10px_rgba(212,175,55,0.5)]"
                 :style="{ width: `${mediaStore.uploadProgress}%` }"
               ></div>
            </div>
            <p class="mt-2 text-[10px] font-black text-amber-500 uppercase tracking-widest">Uploading {{ mediaStore.uploadProgress }}%</p>
         </div>
      </div>
    </div>

    <!-- Media Grid -->
    <div class="space-y-8">
       <div class="flex items-center justify-between px-4">
          <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em]">Asset Collection</h3>
          <div class="flex items-center gap-4">
             <span class="text-[10px] font-bold text-slate-600 uppercase">{{ mediaStore.items.length }} Files</span>
          </div>
       </div>

       <div v-if="mediaStore.isLoading && !mediaStore.items.length" class="p-40 text-center">
          <div class="w-16 h-16 border-4 border-amber-500/20 border-t-amber-500 rounded-full animate-spin mx-auto"></div>
       </div>

       <div v-else-if="!mediaStore.items.length" class="p-40 text-center space-y-4">
          <div class="w-20 h-20 mx-auto bg-slate-950 rounded-3xl flex items-center justify-center text-slate-800 border border-slate-800">
             <ImageIcon class="w-10 h-10" />
          </div>
          <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.4em]">Your library is empty</p>
       </div>

       <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">
          <div v-for="item in mediaStore.items" :key="item.id" class="group relative aspect-square bg-slate-900 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-amber-500/30 transition-all shadow-xl">
             <img :src="item.url" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-60 group-hover:opacity-100" />
             
             <!-- Overlay -->
             <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center gap-4 p-4">
                <p class="text-[10px] font-bold text-white truncate w-full text-center mb-2 px-2 uppercase tracking-tighter">{{ item.filename }}</p>
                <div class="flex gap-2">
                   <button @click="copyUrl(item.url)" class="w-10 h-10 bg-white text-slate-950 rounded-xl flex items-center justify-center hover:bg-amber-500 transition-colors shadow-2xl" title="Copy URL">
                      <Copy class="w-4 h-4" />
                   </button>
                   <button @click="confirmDelete(item)" class="w-10 h-10 bg-red-500 text-white rounded-xl flex items-center justify-center hover:bg-red-600 transition-colors shadow-2xl" title="Delete">
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
      title="Discard Asset"
      message="Are you sure you want to permanently remove this image? This will break any content using this URL."
      @cancel="showDeleteConfirm = false"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useMediaStore } from '@/stores/mediaStore'
import { useToast } from '@/composables/useToast'
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
        toast.success(`Successfully uploaded ${files.length} assets`)
    } catch (err) {
        toast.error('Failed to upload some assets')
    }
}

const copyUrl = (url) => {
    navigator.clipboard.writeText(url)
    toast.info('Asset URL copied to clipboard')
}

const confirmDelete = (item) => {
    itemToDelete.value = item
    showDeleteConfirm.value = true
}

const handleDelete = async () => {
    if (!itemToDelete.value) return
    try {
        await mediaStore.deleteMedia(itemToDelete.value.id)
        toast.success('Asset removed successfully')
        showDeleteConfirm.value = false
        itemToDelete.value = null
    } catch (err) {
        toast.error('Failed to remove asset')
    }
}
</script>
