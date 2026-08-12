<template>
  <main class="bg-canvas">
    <PageHeader
      :title="$t('projects.art')"
      :lede="$t('projects.portfolio')"
      :image="banner"
      :image-alt="banner.alt[localeStore.isArabic ? 'ar' : 'en']"
    />

    <section class="py-16 sm:py-20">
      <div class="container mx-auto px-6 sm:px-8">
        <CategoryFilter :active-category="activeCategory" @filter="handleFilter" />

        <!-- `aria-busy` tells assistive tech the grid is refreshing after a
             filter change, instead of silently swapping content underneath. -->
        <div :aria-busy="projectStore.loading ? 'true' : 'false'">
          <div v-if="projectStore.loading" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="i in 6" :key="i" class="animate-pulse">
              <div class="aspect-[4/5] w-full bg-canvas-inset"></div>
              <div class="mt-4 h-4 w-2/3 bg-canvas-inset"></div>
            </div>
          </div>

          <div v-else-if="projectStore.projects.length" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            <ProjectCard
              v-for="project in projectStore.projects"
              :key="project.id"
              :project="project"
            />
          </div>

          <div v-else class="border border-line py-20 text-center">
            <EaiIcon name="image" :size="40" :stroke="1" class="mx-auto text-line-strong" />
            <h2 class="mt-5 text-subhead font-medium text-ink">{{ $t('projects.empty') }}</h2>
            <p class="mx-auto mt-2 max-w-prose text-ink-muted">{{ $t('projects.empty_desc') }}</p>
            <button
              v-if="activeCategory"
              type="button"
              class="mt-6 rounded-xs border border-ink/25 px-6 py-3 text-label font-semibold text-ink transition duration-base ease-out-quart hover:bg-ink hover:text-canvas"
              @click="handleFilter('')"
            >
              {{ $t('projects.filter_all') }}
            </button>
          </div>
        </div>

        <div v-if="hasMore" class="mt-16 flex justify-center">
          <button
            :disabled="projectStore.loading"
            class="rounded-xs border border-ink/25 px-10 py-4 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:border-ink hover:bg-ink hover:text-canvas disabled:opacity-50"
            @click="loadMore"
          >
            {{ projectStore.loading ? $t('projects.loading') : $t('projects.load_more') }}
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

import PageHeader from '@/components/public/PageHeader.vue'
import ProjectCard from '@/components/public/ProjectCard.vue'
import CategoryFilter from '@/components/public/CategoryFilter.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { PAGE_BANNERS } from '@/lib/media'

const route = useRoute()
const router = useRouter()
const projectStore = useProjectStore()
const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const banner = PAGE_BANNERS.projects

useSeo(currentPage)

const activeCategory = computed(() => route.query.category || '')

const hasMore = computed(() => {
  const p = projectStore.pagination
  return Boolean(p && p.current_page < p.last_page)
})

const handleFilter = (category) => {
  router.push({ query: category ? { category } : {} })
}

const fetchProjects = async () => {
  await projectStore.fetchProjects({ category: activeCategory.value })
}

const loadMore = async () => {
  if (!hasMore.value) return
  await projectStore.fetchProjects({
    category: activeCategory.value,
    page: projectStore.pagination.current_page + 1,
    append: true,
  })
}

onMounted(() => {
  pageStore.fetchPage('projects')
})

watch(() => route.query.category, fetchProjects, { immediate: true })
</script>
