<template>
  <button 
    @click="$emit('toggle')" 
    class="relative z-50 flex flex-col items-center justify-center w-10 h-10 gap-1.5 transition-all duration-300 rounded-lg hover:bg-white/5"
    aria-label="Toggle Menu"
  >
    <span 
      class="w-6 h-0.5 bg-white transition-all duration-300 rounded-full" 
      :class="{ 'rotate-45 translate-y-2': isOpen }"
    ></span>
    <span 
      class="w-6 h-0.5 bg-white transition-all duration-300 rounded-full" 
      :class="{ 'opacity-0': isOpen }"
    ></span>
    <span 
      class="w-6 h-0.5 bg-white transition-all duration-300 rounded-full" 
      :class="{ '-rotate-45 -translate-y-2': isOpen }"
    ></span>
  </button>
</template>

<script setup>
defineProps(['isOpen'])
defineEmits(['toggle'])
</script>
