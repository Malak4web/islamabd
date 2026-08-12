<template>
  <div v-if="pageStore.isLoading" class="flex min-h-[62svh] items-center justify-center bg-canvas">
    <span class="sr-only">{{ $t('common.loading') }}</span>
    <div
      class="h-10 w-10 animate-spin rounded-pill border-2 border-line border-t-gold-deep"
      role="status"
      aria-live="polite"
    ></div>
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

        <!-- The walk. Not a CMS section — it is the site's own argument, made in
             the studio's photography, and it lands directly after the studio has
             introduced itself so it reads as proof rather than as a showreel. -->
        <VillaWalk v-if="section.key === walkAnchor" />
    </div>

    <!-- Fallback if no sections are in DB for home -->
    <template v-if="!pageStore.currentPage?.sections?.length">
        <HeroSlider />
        <AboutSnippet />
        <VillaWalk />
        <ServicesPreview />
        <ProjectsPreview />
        <CtaBanner />
    </template>
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'

import HeroSlider from '@/components/public/HeroSlider.vue'
import AboutSnippet from '@/components/public/AboutSnippet.vue'
import ServicesPreview from '@/components/public/ServicesPreview.vue'
import ProjectsPreview from '@/components/public/ProjectsPreview.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'
import VillaWalk from '@/components/public/VillaWalk.vue'

const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

/**
 * Which CMS section the walk follows. It belongs after the studio's own
 * introduction; a page whose sections were reordered so that block no longer
 * exists gets it straight after the hero instead, rather than losing it.
 */
const walkAnchor = computed(() => {
    const keys = (pageStore.currentPage?.sections ?? []).map((s) => s.key)
    if (keys.includes('about_intro')) return 'about_intro'
    if (keys.includes('hero')) return 'hero'
    return null
})

// SEO implementation
useSeo(currentPage)

onMounted(async () => {
    await pageStore.fetchPage('home')
})

/**
 * Slides authored in the CMS. Returns null when the page has no hero content,
 * which tells HeroSlider to fall back to its own locally-hosted set — the old
 * behaviour was to hand it three hardcoded Unsplash URLs, so a fresh install
 * hotlinked three 2000px photos on the critical path.
 */
const heroSlides = computed(() => {
    const section = pageStore.currentPage?.sections?.find(s => s.key === 'hero')
    if (!section) return null

    const c = section.content || {}
    const isAr = localeStore.isArabic

    const pick = (slide) => ({
        image: slide.image || null,
        title_top: isAr ? (slide.title_ar_top || slide.title_ar) : (slide.title_en_top || slide.title_en),
        title_bottom: isAr ? (slide.title_ar_bottom || '') : (slide.title_en_bottom || ''),
        subtitle: isAr ? slide.subtitle_ar : slide.subtitle_en,
        description: isAr ? slide.description_ar : slide.description_en,
        cta_primary: isAr ? slide.cta_ar : slide.cta_en,
    })

    if (Array.isArray(c.slides) && c.slides.length > 0) return c.slides.map(pick)

    // A single-slide hero authored on the section itself.
    const single = pick(c)
    return single.title_top || single.image ? [single] : null
})
</script>