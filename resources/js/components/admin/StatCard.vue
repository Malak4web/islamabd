<template>
  <div class="p-8 bg-slate-900 border border-slate-800 rounded-3xl group transition-all duration-300 hover:border-amber-500/30">
    <div class="flex items-start justify-between">
      <div class="space-y-4">
        <p class="text-[10px] font-bold tracking-[0.3em] text-slate-500 uppercase">{{ title }}</p>
        <h3 class="text-3xl font-black text-white uppercase">{{ value }}</h3>
      </div>
      <div 
        class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300"
        :class="colorClass"
      >
        <component :is="icon" class="w-6 h-6" />
      </div>
    </div>
    
    <div class="mt-8 pt-6 border-t border-slate-800/50 flex items-center justify-between">
      <RouterLink 
        v-if="link" 
        :to="link" 
        class="text-[10px] font-bold tracking-[0.2em] text-amber-500 uppercase hover:text-white transition-colors"
      >
        View Details &rarr;
      </RouterLink>
      <span v-else class="text-[10px] font-bold tracking-[0.2em] text-slate-600 uppercase">Real-time Data</span>
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
    amber: 'bg-amber-500/10 text-amber-500 group-hover:bg-amber-500 group-hover:text-slate-950',
    blue: 'bg-blue-500/10 text-blue-500 group-hover:bg-blue-500 group-hover:text-white',
    emerald: 'bg-emerald-500/10 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white',
    rose: 'bg-rose-500/10 text-rose-500 group-hover:bg-rose-500 group-hover:text-white'
  }
  return colors[props.color] || colors.amber
})
</script>
