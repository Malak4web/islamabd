<template>
  <div class="p-8 bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl group transition-all duration-300 hover:border-[#C5A880]/50 shadow-sm">
    <div class="flex items-start justify-between">
      <div class="space-y-4">
        <p class="text-[10px] font-bold tracking-[0.3em] text-[#555555] uppercase">{{ title }}</p>
        <h3 class="text-3xl font-black text-[#111111] uppercase">{{ value }}</h3>
      </div>
      <div 
        class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300"
        :class="colorClass"
      >
        <component :is="icon" class="w-6 h-6" />
      </div>
    </div>
    
    <div class="mt-8 pt-6 border-t border-[#E0DACE] flex items-center justify-between">
      <RouterLink 
        v-if="link" 
        :to="link" 
        class="text-[10px] font-bold tracking-[0.2em] text-[#C5A880] uppercase hover:text-[#111111] transition-colors"
      >
        View Details &rarr;
      </RouterLink>
      <span v-else class="text-[10px] font-bold tracking-[0.2em] text-[#555555] uppercase">Real-time Data</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  value: [String, Number],
  icon: [Object, Function],
  link: String,
  color: {
    type: String,
    default: 'amber'
  }
})

const colorClass = computed(() => {
  const colors = {
    amber: 'bg-[#C5A880]/15 text-[#C5A880] group-hover:bg-[#C5A880] group-hover:text-white',
    blue: 'bg-blue-500/10 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
    emerald: 'bg-emerald-500/10 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white',
    rose: 'bg-rose-500/10 text-rose-600 group-hover:bg-rose-600 group-hover:text-white'
  }
  return colors[props.color] || colors.amber
})
</script>
