<template>
  <Transition name="fade">
    <div v-if="isOpen && contact" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-[#111111]/40 backdrop-blur-md" @click.self="$emit('close')">
      <div class="w-full max-w-3xl bg-[#FFFFFF] border border-[#E0DACE] rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-10 border-b border-[#E0DACE] flex justify-between items-start shrink-0">
          <div class="space-y-1">
             <div class="flex items-center gap-3">
                <h2 class="text-3xl font-black text-[#111111] uppercase tracking-tighter">{{ contact.name }}</h2>
                <span 
                  class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter"
                  :class="{
                    'bg-red-500/10 text-red-500': contact.status === 'new',
                    'bg-[#F0ECE1] text-[#555555]': contact.status === 'read',
                    'bg-emerald-500/10 text-emerald-500': contact.status === 'replied'
                  }"
                >
                  {{ contact.status === 'new' ? $t('admin.status_new') : (contact.status === 'read' ? $t('admin.status_read') : $t('admin.status_replied')) }}
                </span>
             </div>
             <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">{{ $t('admin.received_on') }} {{ new Date(contact.created_at).toLocaleString() }}</p>
          </div>
          <button @click="$emit('close')" class="w-12 h-12 flex items-center justify-center text-[#555555] hover:text-[#111111] transition-colors bg-[#F7F5F0] rounded-2xl border border-[#E0DACE]">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Content -->
        <div class="flex-1 p-10 overflow-y-auto space-y-10 custom-scrollbar text-start">
           <!-- Fast Info -->
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="p-6 bg-[#F7F5F0] border border-[#E0DACE] rounded-2xl space-y-4 group">
                 <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.phone_number') }}</span>
                    <button @click="copy(contact.phone)" class="text-[#555555] hover:text-[#C5A880] transition-colors">
                       <Copy class="w-4 h-4" />
                    </button>
                 </div>
                 <p class="text-lg font-bold text-[#111111] selection:bg-[#C5A880] selection:text-white">{{ contact.phone }}</p>
              </div>
              <div class="p-6 bg-[#F7F5F0] border border-[#E0DACE] rounded-2xl space-y-4 group">
                 <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.email_address') }}</span>
                    <button @click="copy(contact.email)" class="text-[#555555] hover:text-[#C5A880] transition-colors">
                       <Copy class="w-4 h-4" />
                    </button>
                 </div>
                 <p class="text-lg font-bold text-[#111111] selection:bg-[#C5A880] selection:text-white">{{ contact.email || '—' }}</p>
              </div>
              <div class="p-6 bg-[#F7F5F0] border border-[#E0DACE] rounded-2xl space-y-4 col-span-full">
                 <span class="text-[10px] font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.requested_service') }}</span>
                 <p class="text-lg font-bold text-[#C5A880] uppercase tracking-tight">{{ contact.service || $t('admin.general_inquiry') }}</p>
              </div>
           </div>

           <!-- Message -->
           <div class="space-y-6">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.inquiry_content') }}</label>
              <div class="p-10 bg-[#F7F5F0] border border-[#E0DACE] rounded-[2.5rem] text-[#222222] leading-relaxed font-medium whitespace-pre-wrap shadow-inner">
                 {{ contact.message }}
              </div>
           </div>
        </div>

        <!-- Actions -->
        <div class="p-10 bg-[#F7F5F0] border-t border-[#E0DACE] flex justify-between items-center shrink-0">
          <div class="flex gap-4">
            <button 
              v-if="contact.status === 'new'" 
              @click="$emit('mark-read')"
              class="px-8 py-4 bg-[#F0ECE1] text-[#111111] border border-[#E0DACE] font-bold text-[10px] uppercase tracking-widest rounded-2xl hover:bg-[#E0DACE] transition-all active:scale-95"
            >
              {{ $t('admin.mark_read') }}
            </button>
            <button 
              v-if="contact.status !== 'replied'" 
              @click="$emit('mark-replied')"
              class="px-8 py-4 bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 font-bold text-[10px] uppercase tracking-widest rounded-2xl hover:bg-emerald-500 hover:text-white transition-all active:scale-95"
            >
              {{ $t('admin.mark_replied') }}
            </button>
          </div>
          <button @click="$emit('delete')" class="px-8 py-4 text-red-500 hover:text-red-600 font-bold text-[10px] uppercase tracking-widest transition-colors">
            {{ $t('admin.discard') }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { X, Copy, CheckCircle } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  isOpen: Boolean,
  contact: Object
})

const emit = defineEmits(['close', 'mark-read', 'mark-replied', 'delete'])

const { toast } = useToast()
const { t } = useI18n()

const copy = (text) => {
  if (!text) return
  navigator.clipboard.writeText(text)
  toast.info(t('admin.copied_clipboard'))
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #E0DACE; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #C5A880; }
</style>
