<template>
  <main class="bg-[#0a0a0a]">
    <!-- Page Header -->
    <section class="relative py-48 md:py-64 overflow-hidden bg-black flex items-center justify-center">
      <!-- Background with Ken Burns animation -->
      <div 
        class="absolute inset-0 bg-center bg-cover scale-110 animate-ken-burns opacity-60" 
        :style="{ backgroundImage: 'url(/images/projects-bg.png)' }"
      ></div>
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-[#0a0a0a]"></div>
      
      <div class="relative container px-6 mx-auto text-center z-10">
        <span class="text-sm md:text-base font-black tracking-[0.6em] text-[#d4af37] uppercase mb-8 block animate-fade-in-up">
            {{ $t('projects.portfolio') }}
        </span>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-black text-white uppercase tracking-tighter leading-tight animate-fade-in-up delay-100">
           {{ $t('projects.art') }}
        </h1>
        
        <!-- Architectural Decorative Line -->
        <div class="w-24 h-[2px] bg-[#d4af37] mx-auto mt-12 animate-scale-x"></div>
      </div>
    </section>

    <!-- Projects Section -->
    <section class="py-16 md:py-24">
      <div class="container px-6 mx-auto">
        <!-- Category Filter -->
        <CategoryFilter 
          :activeCategory="activeCategory" 
          @filter="handleFilter" 
        />

        <!-- Projects Grid -->
        <div v-if="projectStore.isLoading" class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
           <div v-for="i in 6" :key="i" class="h-[500px] rounded-[2rem] bg-white/5 animate-pulse"></div>
        </div>
        
        <div v-else-if="projectStore.projects.length" class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
          <ProjectCard 
            v-for="project in projectStore.projects" 
            :key="project.id" 
            :project="project"
          />
        </div>
        
        <!-- Empty State -->
        <div v-else class="py-32 text-center">
           <div class="inline-flex items-center justify-center w-24 h-24 mb-8 rounded-full bg-white/5 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2 2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
           </div>
           <h3 class="text-xl font-bold text-gray-500 uppercase tracking-widest">{{ $t('projects.empty') }}</h3>
           <p class="mt-4 text-gray-600">{{ $t('projects.empty_desc') }}</p>
        </div>

        <!-- Pagination (Load More) -->
        <div v-if="projectStore.pagination?.next_page_url" class="flex justify-center mt-24">
           <button 
             @click="loadMore" 
             :disabled="projectStore.isLoading"
             class="px-16 py-5 text-xs font-bold tracking-[0.3em] text-white uppercase border border-white/10 rounded-full transition-all duration-300 hover:bg-[#d4af37] hover:text-black hover:border-[#d4af37] disabled:opacity-50"
           >
              {{ projectStore.isLoading ? $t('projects.loading') : $t('projects.load_more') }}
           </button>
        </div>
      </div>
    </section>

    <CtaBanner />
  </main>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProjectStore } from '@/stores/projectStore'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'

import ProjectCard from '@/components/public/ProjectCard.vue'
import CategoryFilter from '@/components/public/CategoryFilter.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'

const route = useRoute()
const router = useRouter()
const projectStore = useProjectStore()
const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const isAr = computed(() => localeStore.isArabic)

useSeo(currentPage)

const activeCategory = computed(() => route.query.category || '')

const handleFilter = (category) => {
    if (category) {
        router.push({ query: { category } })
    } else {
        router.push({ query: {} })
    }
}

const fetchProjects = async () => {
    await projectStore.fetchProjects({ 
        category: activeCategory.value 
    })
}

const loadMore = async () => {
    if (projectStore.pagination?.current_page < projectStore.pagination?.last_page) {
        await projectStore.fetchProjects({ 
            category: activeCategory.value,
            page: projectStore.pagination.current_page + 1,
            append: true
        })
    }
}

onMounted(() => {
    pageStore.fetchPage('projects')
})

watch(() => route.query.category, () => {
    fetchProjects()
}, { immediate: true })
</script>
