<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-[#111111]/40 backdrop-blur-sm">
      <div class="w-full max-w-md bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-10 space-y-8 text-center">
          <div class="w-20 h-20 mx-auto bg-red-500/10 text-red-500 rounded-3xl flex items-center justify-center">
             <AlertTriangle class="w-10 h-10" />
          </div>
          
          <div class="space-y-4">
            <h3 class="text-2xl font-black text-[#111111] uppercase tracking-tighter">{{ title }}</h3>
            <p class="text-sm text-[#555555] leading-relaxed">{{ message }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <button 
              @click="handleCancel"
              class="px-8 py-4 text-[10px] font-bold text-[#555555] uppercase tracking-widest bg-[#F0ECE1] border border-[#E0DACE] rounded-2xl hover:bg-[#E0DACE] hover:text-[#111111] transition-colors"
            >
              Cancel
            </button>
            <button 
              @click="handleConfirm"
              class="px-8 py-4 text-[10px] font-bold text-white uppercase tracking-widest bg-red-500 rounded-2xl hover:bg-red-600 shadow-lg shadow-red-500/20 transition-all active:scale-95"
            >
              Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { AlertTriangle } from 'lucide-vue-next'

defineProps({
  isOpen: Boolean,
  title: {
    type: String,
    default: 'Are you sure?'
  },
  message: {
    type: String,
    default: 'This action cannot be undone.'
  }
})

const emit = defineEmits(['confirm', 'cancel'])

const handleConfirm = () => emit('confirm')
const handleCancel = () => emit('cancel')
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
