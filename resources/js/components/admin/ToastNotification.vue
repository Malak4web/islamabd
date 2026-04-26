<template>
  <div class="fixed bottom-8 right-8 z-[200] space-y-4 pointer-events-none">
    <TransitionGroup name="toast">
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        class="pointer-events-auto flex items-center gap-4 px-6 py-4 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl min-w-[320px] max-w-md animate-in slide-in-from-right-10 duration-300"
      >
        <div 
          class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
          :class="{
            'bg-emerald-500/10 text-emerald-500': toast.type === 'success',
            'bg-red-500/10 text-red-500': toast.type === 'error',
            'bg-blue-500/10 text-blue-500': toast.type === 'info'
          }"
        >
          <CheckCircle v-if="toast.type === 'success'" class="w-5 h-5" />
          <AlertCircle v-else-if="toast.type === 'error'" class="w-5 h-5" />
          <Info v-else class="w-5 h-5" />
        </div>
        
        <div class="flex-1 space-y-0.5">
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">
            {{ toast.type }}
          </p>
          <p class="text-xs font-bold text-white">{{ toast.message }}</p>
        </div>

        <button @click="removeToast(toast.id)" class="text-slate-700 hover:text-white transition-colors">
          <X class="w-4 h-4" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToast } from '@/composables/useToast'
import { CheckCircle, AlertCircle, Info, X } from 'lucide-vue-next'

const { toasts, removeToast } = useToast()
</script>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.toast-enter-from { opacity: 0; transform: translateX(50px); }
.toast-leave-to { opacity: 0; transform: scale(0.9); }
</style>
