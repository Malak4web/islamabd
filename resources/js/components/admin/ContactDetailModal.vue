<template>
  <Transition name="fade">
    <div v-if="isOpen && contact" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-950/90 backdrop-blur-md" @click.self="$emit('close')">
      <div class="w-full max-w-3xl bg-slate-900 border border-slate-800 rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-10 border-b border-slate-800 flex justify-between items-start shrink-0">
          <div class="space-y-1">
             <div class="flex items-center gap-3">
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter">{{ contact.name }}</h2>
                <span 
                  class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter"
                  :class="{
                    'bg-red-500/10 text-red-500': contact.status === 'new',
                    'bg-slate-700/20 text-slate-400': contact.status === 'read',
                    'bg-emerald-500/10 text-emerald-500': contact.status === 'replied'
                  }"
                >
                  {{ contact.status }}
                </span>
             </div>
             <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Received on {{ new Date(contact.created_at).toLocaleString() }}</p>
          </div>
          <button @click="$emit('close')" class="w-12 h-12 flex items-center justify-center text-slate-500 hover:text-white transition-colors bg-white/5 rounded-2xl">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Content -->
        <div class="flex-1 p-10 overflow-y-auto space-y-10 custom-scrollbar">
           <!-- Fast Info -->
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="p-6 bg-slate-950 border border-slate-800 rounded-2xl space-y-4 group">
                 <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Phone Number</span>
                    <button @click="copy(contact.phone)" class="text-slate-700 hover:text-amber-500 transition-colors">
                       <Copy class="w-4 h-4" />
                    </button>
                 </div>
                 <p class="text-lg font-bold text-white selection:bg-amber-500 selection:text-slate-950">{{ contact.phone }}</p>
              </div>
              <div class="p-6 bg-slate-950 border border-slate-800 rounded-2xl space-y-4 group">
                 <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Email Address</span>
                    <button @click="copy(contact.email)" class="text-slate-700 hover:text-amber-500 transition-colors">
                       <Copy class="w-4 h-4" />
                    </button>
                 </div>
                 <p class="text-lg font-bold text-white selection:bg-amber-500 selection:text-slate-950">{{ contact.email || 'N/A' }}</p>
              </div>
              <div class="p-6 bg-slate-950 border border-slate-800 rounded-2xl space-y-4 col-span-full">
                 <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Requested Service</span>
                 <p class="text-lg font-bold text-amber-500 uppercase tracking-tight">{{ contact.service || 'General Inquiry' }}</p>
              </div>
           </div>

           <!-- Message -->
           <div class="space-y-6">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest">Inquiry Content</label>
              <div class="p-10 bg-slate-950 border border-slate-800 rounded-[2.5rem] text-slate-300 leading-relaxed font-medium whitespace-pre-wrap shadow-inner">
                 {{ contact.message }}
              </div>
           </div>
        </div>

        <!-- Actions -->
        <div class="p-10 bg-slate-950/50 border-t border-slate-800 flex justify-between items-center shrink-0">
          <div class="flex gap-4">
            <button 
              v-if="contact.status === 'new'" 
              @click="$emit('mark-read')"
              class="px-8 py-4 bg-slate-800 text-white font-bold text-[10px] uppercase tracking-widest rounded-2xl hover:bg-slate-700 transition-all active:scale-95"
            >
              Mark as Read
            </button>
            <button 
              v-if="contact.status !== 'replied'" 
              @click="$emit('mark-replied')"
              class="px-8 py-4 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 font-bold text-[10px] uppercase tracking-widest rounded-2xl hover:bg-emerald-500 hover:text-white transition-all active:scale-95"
            >
              Mark Replied
            </button>
          </div>
          <button @click="$emit('delete')" class="px-8 py-4 text-red-500 hover:text-red-400 font-bold text-[10px] uppercase tracking-widest transition-colors">
            Discard
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { X, Copy, CheckCircle } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  isOpen: Boolean,
  contact: Object
})

const emit = defineEmits(['close', 'mark-read', 'mark-replied', 'delete'])

const { toast } = useToast()

const copy = (text) => {
  if (!text) return
  navigator.clipboard.writeText(text)
  toast.info('Copied to clipboard')
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d4af37; }
</style>
