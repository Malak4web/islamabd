<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-[#111111]/40 backdrop-blur-md">
      <div class="w-full max-w-xl bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-8 border-b border-[#E0DACE] flex justify-between items-center">
          <div class="space-y-1">
            <h2 class="text-2xl font-black text-[#111111] uppercase tracking-tighter">
              {{ isEditing ? $t('admin.edit_user') || 'Edit Team Member' : $t('admin.add_user') || 'Add Team Member' }}
            </h2>
            <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">
              {{ isEditing ? $t('admin.edit_user_desc') || 'Modify member permissions and profile' : $t('admin.add_user_desc') || 'Invite a new admin or data entry member' }}
            </p>
          </div>
          <button @click="$emit('close')" class="w-10 h-10 flex items-center justify-center text-[#555555] hover:text-[#111111] transition-colors bg-[#F7F5F0] rounded-xl border border-[#E0DACE]">
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Form Body -->
        <div class="p-8 space-y-6 overflow-y-auto">
          <!-- Full Name -->
          <div class="space-y-2">
            <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">
              {{ $t('admin.tbl_name') || 'Full Name' }} <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.name" 
              type="text" 
              placeholder="e.g. John Doe"
              class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-4 rounded-xl outline-none focus:border-[#C5A880] transition-all text-xs font-bold text-[#111111]"
              required
            >
          </div>

          <!-- Email Address -->
          <div class="space-y-2">
            <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">
              {{ $t('admin.email') || 'Email Address' }} <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.email" 
              type="email" 
              placeholder="member@eslamabdulghanidesigns.com"
              class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-4 rounded-xl outline-none focus:border-[#C5A880] transition-all text-xs font-bold text-[#111111]"
              required
            >
          </div>

          <!-- Role Selection Cards -->
          <div class="space-y-2">
            <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">
              {{ $t('admin.role') || 'System Role & Permissions' }} <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Admin Role Card -->
              <div 
                @click="form.role = 'admin'"
                class="p-4 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between"
                :class="form.role === 'admin' ? 'border-[#C5A880] bg-[#C5A880]/10 shadow-sm' : 'border-[#E0DACE] bg-[#F7F5F0] hover:border-[#C5A880]/50'"
              >
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2">
                    <ShieldCheck class="w-4 h-4 text-[#C5A880]" />
                    <span class="text-xs font-black uppercase text-[#111111]">{{ $t('admin.role_admin') || 'Administrator' }}</span>
                  </div>
                  <div class="w-4 h-4 rounded-full border flex items-center justify-center" :class="form.role === 'admin' ? 'border-[#C5A880] bg-[#C5A880]' : 'border-[#CCCCCC]'">
                    <div v-if="form.role === 'admin'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                  </div>
                </div>
                <p class="text-[10px] text-[#555555] leading-relaxed">
                  {{ $t('admin.role_desc_admin') || 'Full control over settings, members, code injections, and content.' }}
                </p>
              </div>

              <!-- Data Entry Role Card -->
              <div 
                @click="form.role = 'data_entry'"
                class="p-4 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between"
                :class="form.role === 'data_entry' ? 'border-emerald-600 bg-emerald-500/10 shadow-sm' : 'border-[#E0DACE] bg-[#F7F5F0] hover:border-emerald-500/50'"
              >
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2">
                    <FileEdit class="w-4 h-4 text-emerald-600" />
                    <span class="text-xs font-black uppercase text-[#111111]">{{ $t('admin.role_data_entry') || 'Data Entry' }}</span>
                  </div>
                  <div class="w-4 h-4 rounded-full border flex items-center justify-center" :class="form.role === 'data_entry' ? 'border-emerald-600 bg-emerald-600' : 'border-[#CCCCCC]'">
                    <div v-if="form.role === 'data_entry'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                  </div>
                </div>
                <p class="text-[10px] text-[#555555] leading-relaxed">
                  {{ $t('admin.role_desc_data_entry') || 'Can only manage projects, services, media, and contact messages.' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Password -->
          <div class="space-y-2">
            <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">
              {{ $t('admin.password') || 'Password' }} 
              <span v-if="!isEditing" class="text-red-500">*</span>
              <span v-else class="text-[#888888] font-normal normal-case">({{ $t('admin.leave_blank_keep') || 'leave blank to keep current' }})</span>
            </label>
            <input 
              v-model="form.password" 
              type="password" 
              :placeholder="isEditing ? '••••••••' : 'Minimum 8 characters'"
              class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-4 rounded-xl outline-none focus:border-[#C5A880] transition-all text-xs font-bold text-[#111111]"
              :required="!isEditing"
            >
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-[#F7F5F0] border-t border-[#E0DACE] flex justify-end gap-4">
          <button @click="$emit('close')" type="button" class="px-6 py-3 text-[10px] font-bold text-[#555555] uppercase tracking-widest hover:text-[#111111] transition-colors">
            {{ $t('admin.discard') || 'Cancel' }}
          </button>
          <button 
            @click="$emit('save')"
            :disabled="loading"
            type="button"
            class="px-8 py-3 bg-[#C5A880] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl hover:bg-[#111111] transition-all shadow-md shadow-[#C5A880]/10 disabled:opacity-50"
          >
            {{ loading ? ($t('admin.saving') || 'Saving...') : (isEditing ? ($t('admin.save') || 'Save Changes') : ($t('admin.add_new') || 'Add Member')) }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { X, ShieldCheck, FileEdit } from 'lucide-vue-next'

defineProps({
  isOpen: Boolean,
  isEditing: Boolean,
  form: Object,
  loading: Boolean
})

defineEmits(['close', 'save'])
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
