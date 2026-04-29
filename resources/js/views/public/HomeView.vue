<template>
  <div v-if="pageStore.isLoading" class="flex items-center justify-center min-h-screen bg-black">
    <div class="w-16 h-16 border-4 border-[#d4af37]/20 border-t-[#d4af37] rounded-full animate-spin"></div>
  </div>

  <main v-else class="relative">
    <!-- Sections from DB -->
    <div v-for="section in pageStore.currentPage?.sections" :key="section.id">
        <!-- Hero Section -->
        <HeroSlider v-if="section.key === 'hero'" :slides="heroSlides" />

        <!-- About Snippet -->
        <AboutSnippet v-if="section.key === 'about_intro'" :content="section.content" />
        
        <!-- Services Preview -->
        <ServicesPreview v-if="section.key === 'services_overview'" :content="section.content" />
        
        <!-- Projects Preview -->
        <ProjectsPreview v-if="section.key === 'projects_grid'" :content="section.content" />
        
        <!-- CTA Banner -->
        <CtaBanner v-if="section.key === 'cta_consultation'" :content="section.content" />
    </div>

    <!-- Fallback if no sections are in DB for home -->
    <template v-if="!pageStore.currentPage?.sections?.length">
        <HeroSlider />
        <AboutSnippet />
        <ServicesPreview />
        <ProjectsPreview />
        <CtaBanner />
    </template>
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { usePageStore } from '@/stores/pageStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'

import HeroSlider from '@/components/public/HeroSlider.vue'
import AboutSnippet from '@/components/public/AboutSnippet.vue'
import ServicesPreview from '@/components/public/ServicesPreview.vue'
import ProjectsPreview from '@/components/public/ProjectsPreview.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'

const pageStore = usePageStore()
const { currentPage } = storeToRefs(pageStore)

// SEO implementation
useSeo(currentPage)

onMounted(async () => {
    await pageStore.fetchPage('home')
})

const heroSlides = computed(() => {
    const section = pageStore.currentPage?.sections?.find(s => s.key === 'hero')
    if (!section) return [
        { 
            image: '/images/defaults/hero_fallback.jpg',
            title_top: 'Innovative',
            title_bottom: 'Architecture',
            subtitle: 'Crafting Excellence',
            description: 'We transform visions into timeless structures, blending luxury with functional brilliance.'
        }
    ]

    const c = section.content
    const isAr = localStorage.getItem('locale') === 'ar'
    
    return [
        {
            image: c.image || '/images/defaults/hero_fallback.jpg',
            title_top: isAr ? (c.title_ar_top || c.title_ar) : (c.title_en_top || c.title_en),
            title_bottom: isAr ? (c.title_ar_bottom || '') : (c.title_en_bottom || ''),
            subtitle: isAr ? c.subtitle_ar : c.subtitle_en,
            description: isAr ? c.description_ar : c.description_en,
            cta_primary: isAr ? c.cta_ar : c.cta_en
        }
    ]
})
</script>