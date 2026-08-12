<template>
  <div class="space-y-10">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-[#111111] uppercase tracking-tighter">{{ $t('admin.services') }}</h1>
        <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">{{ $t('admin.services_manage') }}</p>
      </div>
      <button 
        @click="openAddModal"
        class="flex items-center gap-3 px-8 py-4 bg-[#C5A880] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-[#111111] hover:scale-105 active:scale-95 transition-all shadow-xl shadow-[#C5A880]/10"
      >
        <Plus class="w-4 h-4" />
        {{ $t('admin.new_service') }}
      </button>
    </div>

    <!-- Stats Preview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div class="p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl flex items-center justify-between shadow-sm">
          <div class="space-y-1">
             <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.total_active') }}</p>
             <h4 class="text-2xl font-black text-[#111111]">{{ serviceStore.services.filter(s => s.is_active).length }}</h4>
          </div>
          <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 rounded-xl flex items-center justify-center">
             <CheckCircle class="w-5 h-5" />
          </div>
       </div>
       <div class="p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl flex items-center justify-between shadow-sm">
          <div class="space-y-1">
             <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.drafts') }}</p>
             <h4 class="text-2xl font-black text-[#111111]">{{ serviceStore.services.filter(s => !s.is_active).length }}</h4>
          </div>
          <div class="w-10 h-10 bg-[#C5A880]/15 text-[#C5A880] rounded-xl flex items-center justify-center">
             <Edit3 class="w-5 h-5" />
          </div>
       </div>
       <div class="p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl flex items-center justify-between shadow-sm">
          <div class="space-y-1">
             <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.display_order') }}</p>
             <h4 class="text-2xl font-black text-[#111111]">{{ $t('admin.manual') }}</h4>
          </div>
          <div class="w-10 h-10 bg-blue-500/10 text-blue-600 rounded-xl flex items-center justify-center">
             <Move class="w-5 h-5" />
          </div>
       </div>
    </div>

    <!-- Draggable List -->
    <div class="bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden shadow-md">
      <div class="grid grid-cols-12 gap-4 p-8 border-b border-[#E0DACE] text-[10px] font-black text-[#555555] uppercase tracking-widest bg-[#F7F5F0] text-start">
        <div class="col-span-1"></div>
        <div class="col-span-5">{{ $t('admin.service_overview') }}</div>
        <div class="col-span-2 text-center">{{ $t('admin.relevance') }}</div>
        <div class="col-span-2 text-center">{{ $t('admin.tbl_status') }}</div>
        <div class="col-span-2 text-end">{{ $t('admin.actions') }}</div>
      </div>

      <draggable 
        v-model="localServices" 
        item-key="id" 
        @end="handleReorder"
        handle=".drag-handle"
        class="divide-y divide-[#E0DACE]"
      >
        <template #item="{ element }">
          <div class="grid grid-cols-12 gap-4 p-8 items-center group hover:bg-[#F0ECE1]/50 transition-colors">
            <div class="col-span-1">
              <div class="drag-handle cursor-grab active:cursor-grabbing p-3 text-[#555555] group-hover:text-[#C5A880] transition-colors bg-[#F7F5F0] rounded-xl border border-transparent group-hover:border-[#C5A880]/30">
                <GripVertical class="w-5 h-5" />
              </div>
            </div>
            <div class="col-span-5 flex items-center gap-6">
              <div class="w-16 h-16 bg-[#F0ECE1] rounded-2xl p-4 border border-[#E0DACE] flex-shrink-0 relative overflow-hidden group-hover:border-[#C5A880]/30 transition-all">
                <img v-if="element.icon" :src="element.icon" class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition-all">
                <div v-else class="w-full h-full flex items-center justify-center text-[#555555]">
                   <Box class="w-6 h-6" />
                </div>
              </div>
              <div class="space-y-1">
                <h3 class="text-base font-bold text-[#111111] group-hover:text-[#C5A880] transition-colors uppercase tracking-tight">{{ element.title_en }}</h3>
                <p class="text-xs text-[#555555] font-bold tracking-widest">{{ element.title_ar }}</p>
              </div>
            </div>
            <div class="col-span-2 text-center">
               <span class="text-xs font-mono font-bold text-[#555555] group-hover:text-[#111111] transition-colors">
                  #{{ element.order }}
               </span>
            </div>
            <div class="col-span-2 text-center">
              <button 
                @click="toggleService(element)"
                data-test-toggle
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter border transition-all"
                :class="element.is_active ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-600' : 'border-[#E0DACE] bg-[#F0ECE1] text-[#555555]'"
              >
                <div class="w-1.5 h-1.5 rounded-full" :class="element.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-[#555555]'"></div>
                {{ element.is_active ? $t('admin.active') : $t('admin.draft') }}
              </button>
            </div>
            <div class="col-span-2 text-end flex justify-end gap-3">
              <button @click="editService(element)" class="w-10 h-10 flex items-center justify-center bg-[#F7F5F0] text-[#555555] hover:text-[#C5A880] hover:border-[#C5A880]/30 border border-[#E0DACE] rounded-xl transition-all">
                <Edit3 class="w-4 h-4" />
              </button>
              <button @click="openDeleteConfirm(element)" class="w-10 h-10 flex items-center justify-center bg-[#F7F5F0] text-[#555555] hover:text-red-500 hover:border-red-500/30 border border-[#E0DACE] rounded-xl transition-all">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </template>
      </draggable>

      <div v-if="!localServices.length" class="p-24 text-center space-y-4">
         <div class="w-20 h-20 mx-auto bg-[#F0ECE1] rounded-3xl flex items-center justify-center text-[#555555] border border-[#E0DACE]">
            <Wrench class="w-10 h-10" />
         </div>
         <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.4em]">{{ $t('admin.no_services_found') }}</p>
      </div>
    </div>

    <!-- Modals -->
    <ServiceFormModal 
      :is-open="showModal"
      :is-editing="isEditing"
      :form="form"
      :loading="serviceStore.isLoading"
      @close="showModal = false"
      @save="saveService"
      @upload="handleUpload"
    />

    <ConfirmModal 
      :is-open="showDeleteConfirm"
      :title="$t('admin.delete_expertise')"
      :message="`${$t('admin.delete_expertise_confirm')} (${serviceToDelete?.title_en})`"
      @cancel="showDeleteConfirm = false"
      @confirm="deleteService"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, watch } from 'vue'
import draggable from 'vuedraggable'
import { useServiceStore } from '@/stores/serviceStore'
import { useI18n } from 'vue-i18n'
import { 
  Plus, 
  GripVertical, 
  Edit3, 
  Trash2, 
  Wrench, 
  CheckCircle, 
  Move,
  Box
} from 'lucide-vue-next'
import ServiceFormModal from '@/components/admin/ServiceFormModal.vue'
import ConfirmModal from '@/components/admin/ConfirmModal.vue'

const serviceStore = useServiceStore()
const { t } = useI18n()
const localServices = ref([])
const showModal = ref(false)
const showDeleteConfirm = ref(false)
const isEditing = ref(false)
const editingId = ref(null)
const serviceToDelete = ref(null)

const form = reactive({
    title_en: '',
    title_ar: '',
    description_en: '',
    description_ar: '',
    icon: null,
    image: null,
    is_active: true
})

onMounted(async () => {
    await serviceStore.fetchAdminServices()
    localServices.value = [...serviceStore.services]
})

watch(() => serviceStore.services, (newVal) => {
    localServices.value = [...newVal]
}, { deep: true })

const openAddModal = () => {
    isEditing.value = false
    editingId.value = null
    Object.assign(form, {
        title_en: '',
        title_ar: '',
        description_en: '',
        description_ar: '',
        icon: null,
        image: null,
        is_active: true
    })
    showModal.value = true
}

const editService = (service) => {
    isEditing.value = true
    editingId.value = service.id
    Object.assign(form, { ...service })
    showModal.value = true
}

const saveService = async () => {
    try {
        if (isEditing.value) {
            await serviceStore.updateService(editingId.value, { ...form })
        } else {
            await serviceStore.createService({ ...form })
        }
        showModal.value = false
    } catch (err) {
        console.error(err)
    }
}

const toggleService = async (service) => {
    await serviceStore.toggleService(service.id)
}

const handleReorder = async () => {
    const ids = localServices.value.map(s => s.id)
    await serviceStore.reorderServices(ids)
    alert(t('admin.reordered_success'))
}

const handleUpload = async ({ event, type }) => {
    const file = event.target.files[0]
    if (!file) return

    try {
        const url = type === 'icon' 
            ? await serviceStore.uploadIcon(editingId.value, file)
            : await serviceStore.uploadImage(editingId.value, file)
        
        form[type] = url
    } catch (err) {
        console.error(err)
    }
}

const openDeleteConfirm = (service) => {
    serviceToDelete.value = service
    showDeleteConfirm.value = true
}

const deleteService = async () => {
    if (!serviceToDelete.value) return
    await serviceStore.deleteService(serviceToDelete.value.id)
    showDeleteConfirm.value = false
    serviceToDelete.value = null
}
</script>
