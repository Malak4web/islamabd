<template>
  <section class="py-32 bg-[#0a0a0a] relative overflow-hidden">
    <!-- Decorative background element -->
    <div class="absolute bottom-0 right-0 w-1/3 h-full bg-[#d4af37]/5 blur-[120px] rounded-full translate-x-1/2"></div>
    <div class="container px-6 mx-auto">
      <div class="flex flex-col items-center justify-between gap-12 mb-24 lg:flex-row">
        <div class="space-y-4 text-center lg:text-left" :class="store.isArabic ? 'lg:text-right' : 'lg:text-left'">
           <span class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase">
                {{ isAr ? (content?.title_ar || $t('projects.title')) : (content?.title_en || $t('projects.title')) }}
           </span>
           <h2 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
              {{ isAr ? (content?.subtitle_ar || $t('projects.subtitle')) : (content?.subtitle_en || $t('projects.subtitle')) }}
           </h2>
        </div>
        
        <RouterLink to="/projects" class="px-12 py-5 text-xs font-bold tracking-[0.2em] text-white uppercase border border-white/10 rounded-full transition-all duration-500 hover:bg-white hover:text-black hover:border-white">
          {{ $t('projects.view_all') }}
        </RouterLink>
      </div>

      <div v-if="projectStore.isLoading" class="grid grid-cols-1 gap-10 md:grid-cols-3">
         <div v-for="i in 3" :key="i" class="h-[600px] rounded-3xl bg-white/5 animate-pulse"></div>
      </div>
      
      <div v-else class="grid grid-cols-1 gap-10 md:grid-cols-3">
        <ProjectCard 
          v-for="project in projectStore.featuredProjects.slice(0, 3)" 
          :key="project.id" 
          :project="project"
          class="aspect-[3/4]"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useProjectStore } from '@/stores/projectStore'
import { useLocaleStore } from '@/stores/localeStore'
import ProjectCard from './ProjectCard.vue'

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    }
})

const projectStore = useProjectStore()
const store = useLocaleStore()
const isAr = computed(() => store.isArabic)

onMounted(() => {
    projectStore.fetchProjects({ featured: 1 })
})
</script>
