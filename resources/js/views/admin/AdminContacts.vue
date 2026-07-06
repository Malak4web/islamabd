<template>
  <div class="space-y-10">
    <!-- Header & Filter Area -->
    <div class="flex items-end justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">{{ $t('admin.inbox') }}</h1>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">{{ $t('admin.contacts_manage') }}</p>
      </div>
      
      <div class="flex gap-2 bg-slate-900 p-1 rounded-2xl border border-slate-800">
        <button 
          v-for="status in ['all', 'new', 'read', 'replied']" 
          :key="status"
          @click="setFilter(status === 'all' ? '' : status)"
          class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all relative"
          :class="currentFilter === (status === 'all' ? '' : status) ? 'bg-amber-500 text-slate-950 shadow-lg' : 'text-slate-500 hover:text-white'"
        >
          {{ status === 'all' ? $t('admin.filter_all') : (status === 'new' ? $t('admin.status_new') : (status === 'read' ? $t('admin.status_read') : $t('admin.status_replied'))) }}
        </button>
      </div>
    </div>

    <!-- Bulk Actions (Float) -->
    <Transition name="slide-up">
      <div v-if="selectedIds.length > 0" class="fixed bottom-12 left-1/2 -translate-x-1/2 z-50 px-8 py-4 bg-slate-900 border border-slate-700 rounded-full shadow-2xl flex items-center gap-8 animate-in slide-in-from-bottom-4 duration-300">
        <div class="flex items-center gap-3">
           <div class="w-8 h-8 bg-amber-500 text-slate-950 rounded-full flex items-center justify-center font-black text-xs">
              {{ selectedIds.length }}
           </div>
           <span class="text-[10px] font-bold text-white uppercase tracking-widest">{{ $t('admin.selected_inquiries') }}</span>
        </div>
        <div class="h-6 w-px bg-slate-800"></div>
        <div class="flex items-center gap-4">
           <button @click="bulkDelete" class="flex items-center gap-2 text-[10px] font-black text-red-500 uppercase tracking-widest hover:text-white transition-colors">
              <Trash2 class="w-4 h-4" /> {{ $t('admin.delete_permanently') }}
           </button>
        </div>
      </div>
    </Transition>

    <!-- Contacts Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
      <div class="grid grid-cols-12 gap-4 p-8 border-b border-slate-800 text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-950/30 text-start">
        <div class="col-span-1 flex justify-center">
          <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" class="w-5 h-5 bg-slate-950 border-slate-800 rounded-lg accent-amber-500 cursor-pointer">
        </div>
        <div class="col-span-4 text-start">{{ $t('admin.sender_overview') }}</div>
        <div class="col-span-3 text-center">{{ $t('admin.inquiry_subject') }}</div>
        <div class="col-span-2 text-center">{{ $t('admin.tbl_status') }}</div>
        <div class="col-span-2 text-end">{{ $t('admin.actions') }}</div>
      </div>

      <div v-if="store.isLoading" class="p-40 text-center space-y-4">
        <div class="w-16 h-16 border-4 border-amber-500/20 border-t-amber-500 rounded-full animate-spin mx-auto"></div>
        <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ $t('admin.syncing_inbox') }}</p>
      </div>

      <div v-else-if="store.contacts.length === 0" class="p-40 text-center space-y-4">
         <div class="w-20 h-20 mx-auto bg-slate-950 rounded-3xl flex items-center justify-center text-slate-800 border border-slate-800">
            <Mail class="w-10 h-10" />
         </div>
         <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.4em]">{{ $t('admin.inbox_clear') }}</p>
      </div>

      <div v-else class="divide-y divide-slate-800/50">
        <div 
          v-for="contact in store.contacts" 
          :key="contact.id" 
          class="grid grid-cols-12 gap-4 p-8 items-center group hover:bg-white/[0.02] transition-colors"
          :class="{'bg-amber-500/[0.02]': contact.status === 'new'}"
        >
          <div class="col-span-1 flex justify-center">
            <input type="checkbox" :value="contact.id" v-model="selectedIds" class="w-5 h-5 bg-slate-950 border-slate-800 rounded-lg accent-amber-500 cursor-pointer">
          </div>
          
          <div class="col-span-4 cursor-pointer space-y-1 text-start" @click="viewContact(contact)">
            <h3 class="text-base font-bold transition-colors group-hover:text-amber-500 truncate uppercase tracking-tight" :class="contact.status === 'new' ? 'text-white' : 'text-slate-500'">
              {{ contact.name }}
            </h3>
            <p class="text-[10px] font-bold text-slate-600 tracking-widest">{{ contact.phone }}</p>
          </div>
          
          <div class="col-span-3 text-center">
             <span class="px-3 py-1 bg-slate-950 text-[10px] font-bold text-slate-500 rounded-lg border border-slate-800 uppercase tracking-widest group-hover:text-amber-500 group-hover:border-amber-500/20 transition-all">
                {{ contact.service || $t('admin.general_inquiry') }}
             </span>
          </div>
          
          <div class="col-span-2 text-center">
            <span 
              class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter border transition-all"
              :class="{
                'border-red-500/20 bg-red-500/10 text-red-500 shadow-lg shadow-red-500/5': contact.status === 'new',
                'border-slate-800 bg-slate-800/50 text-slate-500': contact.status === 'read',
                'border-emerald-500/20 bg-emerald-500/10 text-emerald-500 shadow-lg shadow-emerald-500/5': contact.status === 'replied'
              }"
            >
              <div class="w-1.5 h-1.5 rounded-full" :class="contact.status === 'new' ? 'bg-red-500 animate-pulse' : (contact.status === 'read' ? 'bg-slate-700' : 'bg-emerald-500')"></div>
              {{ contact.status === 'new' ? $t('admin.status_new') : (contact.status === 'read' ? $t('admin.status_read') : $t('admin.status_replied')) }}
            </span>
          </div>
          
          <div class="col-span-2 text-end flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
            <button @click.stop="viewContact(contact)" class="w-10 h-10 flex items-center justify-center bg-slate-950 text-slate-500 hover:text-amber-500 hover:border-amber-500/30 border border-slate-800 rounded-xl transition-all">
              <Eye class="w-4 h-4" />
            </button>
            <button @click.stop="openDeleteConfirm(contact)" class="w-10 h-10 flex items-center justify-center bg-slate-950 text-slate-500 hover:text-red-500 hover:border-red-500/30 border border-slate-800 rounded-xl transition-all">
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="store.pagination.last_page > 1" class="p-8 border-t border-slate-800 bg-slate-950/30 flex justify-between items-center">
        <button 
          :disabled="store.pagination.current_page === 1"
          @click="fetchPage(store.pagination.current_page - 1)"
          class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl border border-slate-800 hover:bg-white hover:text-slate-950 transition-all disabled:opacity-30 disabled:hover:bg-slate-900 disabled:hover:text-white"
        >
          {{ $t('admin.previous') }}
        </button>
        <div class="flex items-center gap-4">
           <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('admin.archive_perspective') }}</span>
           <div class="px-4 py-2 bg-slate-900 rounded-lg text-xs font-bold text-amber-500 border border-slate-800">
              {{ store.pagination.current_page }} / {{ store.pagination.last_page }}
           </div>
        </div>
        <button 
          :disabled="store.pagination.current_page === store.pagination.last_page"
          @click="fetchPage(store.pagination.current_page + 1)"
          class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl border border-slate-800 hover:bg-white hover:text-slate-950 transition-all disabled:opacity-30 disabled:hover:bg-slate-900 disabled:hover:text-white"
        >
          {{ $t('admin.next') }}
        </button>
      </div>
    </div>

    <!-- Modals -->
    <ContactDetailModal 
      :is-open="!!viewingContact"
      :contact="viewingContact"
      @close="viewingContact = null"
      @mark-read="handleMarkRead"
      @mark-replied="handleMarkReplied"
      @delete="handleDeleteSingle"
    />

    <ConfirmModal 
      :is-open="showDeleteConfirm"
      :title="$t('admin.discard_message')"
      :message="`${$t('admin.delete_contact_confirm')} (${contactToDelete?.name})`"
      @cancel="showDeleteConfirm = false"
      @confirm="deleteContact"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useContactStore } from '@/stores/contactStore'
import { useI18n } from 'vue-i18n'
import { 
  Mail, 
  Trash2, 
  Eye, 
  CheckCircle,
  Archive
} from 'lucide-vue-next'
import ContactDetailModal from '@/components/admin/ContactDetailModal.vue'
import ConfirmModal from '@/components/admin/ConfirmModal.vue'

const store = useContactStore()
const { t } = useI18n()
const currentFilter = ref('')
const selectedIds = ref([])
const viewingContact = ref(null)
const showDeleteConfirm = ref(false)
const contactToDelete = ref(null)

onMounted(() => {
    fetchPage(1)
})

const fetchPage = async (page) => {
    await store.fetchAdminContacts({ 
        page, 
        status: currentFilter.value 
    })
    selectedIds.value = []
}

const setFilter = (status) => {
    currentFilter.value = status
    fetchPage(1)
}

const isAllSelected = computed(() => {
    return store.contacts.length > 0 && selectedIds.value.length === store.contacts.length
})

const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedIds.value = []
    } else {
        selectedIds.value = store.contacts.map(c => c.id)
    }
}

const viewContact = (contact) => {
    viewingContact.value = contact
    if (contact.status === 'new') {
        store.markAsRead(contact.id)
    }
}

const handleMarkRead = async () => {
   if (!viewingContact.value) return
   await store.markAsRead(viewingContact.value.id)
   viewingContact.value.status = 'read'
}

const handleMarkReplied = async () => {
   if (!viewingContact.value) return
   await store.markAsReplied(viewingContact.value.id)
   viewingContact.value.status = 'replied'
}

const handleDeleteSingle = () => {
   contactToDelete.value = viewingContact.value
   viewingContact.value = null
   showDeleteConfirm.value = true
}

const openDeleteConfirm = (contact) => {
    contactToDelete.value = contact
    showDeleteConfirm.value = true
}

const deleteContact = async () => {
    if (!contactToDelete.value) return
    await store.deleteContact(contactToDelete.value.id)
    showDeleteConfirm.value = false
    contactToDelete.value = null
}

const bulkDelete = async () => {
    if (confirm(t('admin.confirm_delete') + ' (' + selectedIds.value.length + ')')) {
        await store.bulkDelete(selectedIds.value)
        selectedIds.value = []
    }
}
</script>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.slide-up-enter-from, .slide-up-leave-to { opacity: 0; transform: translate(-50%, 20px); }
</style>
