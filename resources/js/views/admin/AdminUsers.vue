<template>
  <div class="space-y-10">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-4xl font-black text-[#111111] uppercase tracking-tighter">{{ $t('admin.team_management') || 'Team & Permissions' }}</h1>
        <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">{{ $t('admin.team_management_desc') || 'Manage administrators and data entry members' }}</p>
      </div>
      <button 
        @click="openAddModal"
        class="flex items-center gap-3 px-8 py-4 bg-[#C5A880] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-[#111111] hover:scale-105 active:scale-95 transition-all shadow-xl shadow-[#C5A880]/10"
      >
        <UserPlus class="w-4 h-4" />
        {{ $t('admin.new_member') || 'Add Member' }}
      </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl flex items-center justify-between shadow-sm">
        <div class="space-y-1">
          <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.total_members') || 'Total Members' }}</p>
          <h4 class="text-2xl font-black text-[#111111]">{{ adminUserStore.admins.length }}</h4>
        </div>
        <div class="w-10 h-10 bg-[#C5A880]/15 text-[#C5A880] rounded-xl flex items-center justify-center">
          <Users class="w-5 h-5" />
        </div>
      </div>

      <div class="p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl flex items-center justify-between shadow-sm">
        <div class="space-y-1">
          <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.administrators') || 'Administrators' }}</p>
          <h4 class="text-2xl font-black text-[#111111]">{{ adminCount }}</h4>
        </div>
        <div class="w-10 h-10 bg-amber-500/10 text-amber-600 rounded-xl flex items-center justify-center">
          <ShieldCheck class="w-5 h-5" />
        </div>
      </div>

      <div class="p-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl flex items-center justify-between shadow-sm">
        <div class="space-y-1">
          <p class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.data_entry_officers') || 'Data Entry Officers' }}</p>
          <h4 class="text-2xl font-black text-[#111111]">{{ dataEntryCount }}</h4>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 rounded-xl flex items-center justify-center">
          <FileEdit class="w-5 h-5" />
        </div>
      </div>
    </div>

    <!-- Members Table -->
    <div class="bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden shadow-md">
      <div class="grid grid-cols-12 gap-4 p-8 border-b border-[#E0DACE] text-[10px] font-black text-[#555555] uppercase tracking-widest bg-[#F7F5F0] text-start">
        <div class="col-span-5">{{ $t('admin.tbl_name') || 'Member' }}</div>
        <div class="col-span-3 text-center">{{ $t('admin.role') || 'Role & Scope' }}</div>
        <div class="col-span-2 text-center">{{ $t('admin.tbl_date') || 'Registered' }}</div>
        <div class="col-span-2 text-end">{{ $t('admin.actions') || 'Actions' }}</div>
      </div>

      <div class="divide-y divide-[#E0DACE]">
        <div 
          v-for="admin in adminUserStore.admins" 
          :key="admin.id"
          class="grid grid-cols-12 gap-4 p-8 items-center group hover:bg-[#F0ECE1]/50 transition-colors"
        >
          <!-- Member Avatar & Name/Email -->
          <div class="col-span-5 flex items-center gap-4">
            <div 
              class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm uppercase flex-shrink-0 transition-transform group-hover:scale-105 shadow-sm"
              :class="admin.role === 'admin' ? 'bg-[#C5A880] text-white' : 'bg-emerald-600 text-white'"
            >
              {{ admin.name.charAt(0) }}
            </div>
            <div class="space-y-1 min-w-0">
              <div class="flex items-center gap-2">
                <h3 class="text-sm font-bold text-[#111111] group-hover:text-[#C5A880] transition-colors truncate">
                  {{ admin.name }}
                </h3>
                <span 
                  v-if="admin.id === auth.user?.id" 
                  class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-[#111111] text-white"
                >
                  {{ $t('admin.you') || 'You' }}
                </span>
              </div>
              <p class="text-xs text-[#555555] font-medium truncate">{{ admin.email }}</p>
            </div>
          </div>

          <!-- Role Badge -->
          <div class="col-span-3 text-center">
            <span 
              class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border shadow-sm"
              :class="admin.role === 'admin' 
                ? 'border-[#C5A880]/30 bg-[#C5A880]/15 text-[#8F7449]' 
                : 'border-emerald-500/20 bg-emerald-500/10 text-emerald-700'"
            >
              <component :is="admin.role === 'admin' ? ShieldCheck : FileEdit" class="w-3.5 h-3.5" />
              {{ admin.role === 'admin' ? ($t('admin.role_admin') || 'Administrator') : ($t('admin.role_data_entry') || 'Data Entry') }}
            </span>
          </div>

          <!-- Date -->
          <div class="col-span-2 text-center">
            <span class="text-xs font-mono text-[#555555] font-bold">
              {{ formatDate(admin.created_at) }}
            </span>
          </div>

          <!-- Actions -->
          <div class="col-span-2 text-end flex justify-end gap-3">
            <button 
              @click="editMember(admin)"
              class="w-10 h-10 flex items-center justify-center bg-[#F7F5F0] text-[#555555] hover:text-[#C5A880] hover:border-[#C5A880]/30 border border-[#E0DACE] rounded-xl transition-all"
              :title="$t('admin.edit') || 'Edit Member'"
            >
              <Edit3 class="w-4 h-4" />
            </button>
            <button 
              @click="openDeleteConfirm(admin)"
              :disabled="admin.id === auth.user?.id"
              class="w-10 h-10 flex items-center justify-center bg-[#F7F5F0] text-[#555555] hover:text-red-500 hover:border-red-500/30 border border-[#E0DACE] rounded-xl transition-all disabled:opacity-30 disabled:hover:text-[#555555] disabled:cursor-not-allowed"
              :title="admin.id === auth.user?.id ? ($t('admin.cannot_delete_self') || 'Cannot delete your own account') : ($t('admin.delete') || 'Delete Member')"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>

      <div v-if="!adminUserStore.admins.length && !adminUserStore.isLoading" class="p-24 text-center space-y-4">
        <div class="w-20 h-20 mx-auto bg-[#F0ECE1] rounded-3xl flex items-center justify-center text-[#555555] border border-[#E0DACE]">
          <Users class="w-10 h-10" />
        </div>
        <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.4em]">{{ $t('admin.no_members_found') || 'No team members registered' }}</p>
      </div>
    </div>

    <!-- User Modal -->
    <AdminUserModal 
      :is-open="showModal"
      :is-editing="isEditing"
      :form="form"
      :loading="adminUserStore.isLoading"
      @close="showModal = false"
      @save="saveMember"
    />

    <!-- Delete Confirmation -->
    <ConfirmModal 
      :is-open="showDeleteConfirm"
      :title="$t('admin.delete_member_title') || 'Delete Team Member'"
      :message="`${$t('admin.delete_member_confirm') || 'Are you sure you want to revoke access for'} (${memberToDelete?.name})?`"
      @cancel="showDeleteConfirm = false"
      @confirm="deleteMember"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { useAdminUserStore } from '@/stores/adminUserStore'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'
import { useI18n } from 'vue-i18n'
import { 
  Users, 
  UserPlus, 
  ShieldCheck, 
  FileEdit, 
  Edit3, 
  Trash2 
} from 'lucide-vue-next'
import AdminUserModal from '@/components/admin/AdminUserModal.vue'
import ConfirmModal from '@/components/admin/ConfirmModal.vue'

const adminUserStore = useAdminUserStore()
const auth = useAuthStore()
const { t } = useI18n()
const { toast } = useToast()

const showModal = ref(false)
const showDeleteConfirm = ref(false)
const isEditing = ref(false)
const editingId = ref(null)
const memberToDelete = ref(null)

const form = reactive({
  name: '',
  email: '',
  role: 'data_entry',
  password: ''
})

const adminCount = computed(() => {
  return adminUserStore.admins.filter(a => a.role === 'admin').length
})

const dataEntryCount = computed(() => {
  return adminUserStore.admins.filter(a => a.role === 'data_entry').length
})

onMounted(async () => {
  try {
    await adminUserStore.fetchAdmins()
  } catch (err) {
    console.error(err)
  }
})

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const d = new Date(dateStr)
  return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
}

const openAddModal = () => {
  isEditing.value = false
  editingId.value = null
  Object.assign(form, {
    name: '',
    email: '',
    role: 'data_entry',
    password: ''
  })
  showModal.value = true
}

const editMember = (member) => {
  isEditing.value = true
  editingId.value = member.id
  Object.assign(form, {
    name: member.name,
    email: member.email,
    role: member.role || 'data_entry',
    password: ''
  })
  showModal.value = true
}

const saveMember = async () => {
  if (!form.name || !form.email) {
    toast.error('Name and Email are required.')
    return
  }
  if (!isEditing.value && (!form.password || form.password.length < 8)) {
    toast.error('Password must be at least 8 characters.')
    return
  }

  try {
    if (isEditing.value) {
      const payload = {
        name: form.name,
        email: form.email,
        role: form.role
      }
      if (form.password) {
        payload.password = form.password
      }
      await adminUserStore.updateAdmin(editingId.value, payload)
      toast.success(t('admin.saved') || 'Member updated successfully')
    } else {
      await adminUserStore.createAdmin({ ...form })
      toast.success(t('admin.saved') || 'Member created successfully')
    }
    showModal.value = false
  } catch (err) {
    console.error(err)
    toast.error(err?.response?.data?.message || t('admin.error') || 'Failed to save member')
  }
}

const openDeleteConfirm = (member) => {
  memberToDelete.value = member
  showDeleteConfirm.value = true
}

const deleteMember = async () => {
  if (!memberToDelete.value) return
  try {
    await adminUserStore.deleteAdmin(memberToDelete.value.id)
    toast.success('Member removed successfully')
    showDeleteConfirm.value = false
    memberToDelete.value = null
  } catch (err) {
    console.error(err)
    toast.error(err?.response?.data?.message || 'Failed to delete member')
  }
}
</script>
