<template>
  <nav :class="containerClass">
    <RouterLink 
      v-for="link in links" 
      :key="link.name" 
      :to="link.path"
      :class="linkClass || 'relative text-sm font-medium tracking-widest uppercase transition-all duration-300 hover:text-[#d4af37] group'"
      active-class="text-[#d4af37]"
      @click="$emit('linkClick')"
    >
      {{ $t(`nav.${link.key}`) }}
      <span v-if="!linkClass" class="absolute bottom-[-4px] left-0 w-0 h-[2px] bg-[#d4af37] transition-all duration-300 group-hover:w-full" :class="{ 'w-full': $route.path === link.path }"></span>
    </RouterLink>
  </nav>
</template>

<script setup>
defineProps({
  containerClass: {
    type: String,
    default: 'flex items-center gap-8'
  },
  linkClass: {
    type: String,
    default: ''
  }
})

const links = [
  { name: 'Home', path: '/', key: 'home' },
  { name: 'About', path: '/about', key: 'about' },
  { name: 'Services', path: '/services', key: 'services' },
  { name: 'Projects', path: '/projects', key: 'projects' },
  { name: 'Contact', path: '/contact', key: 'contact' }
]
defineEmits(['linkClick'])
</script>
