<template>
  <section class="bg-canvas py-20 sm:py-28 lg:py-32">
    <div class="container mx-auto px-6 sm:px-8">
      <!-- Section head ------------------------------------------------------
           The name leads and the sentence supports it. This block was rendering
           those the other way round: "Every project reflects our commitment to
           excellence…" was set at display size as the h2, while "Featured Work"
           — the section's actual name — was demoted to a tracked uppercase
           eyebrow above it. A sentence is not a heading, and `uppercase` is a
           no-op in Arabic, so the eyebrow shouted in English and spoke normally
           in Arabic: two designs from one template.

           The "+ 03 // ARCHITECTURAL PORTFOLIO" marker is gone with it. A Latin
           string floating over an RTL layout reads as debris, and numbering a
           section that is not part of a sequence is scaffolding, not voice. The
           dimension line below does the architectural work instead, and it
           carries information. -->
      <div class="grid gap-8 lg:grid-cols-12 lg:gap-16">
        <div class="lg:col-span-7">
          <span v-reveal:rule class="block h-px w-14 bg-gold" aria-hidden="true"></span>
          <h2 v-reveal class="mt-7 text-title font-light text-ink">
            {{ heading }}
          </h2>
        </div>

        <!-- Sits on the heading's baseline rather than stacked under it, so the
             two read as one measured line of type across the page. -->
        <p
          v-if="lede"
          v-reveal="{ delay: 90 }"
          class="max-w-prose text-lede text-ink-muted lg:col-span-5 lg:self-end lg:pb-2"
        >
          {{ lede }}
        </p>
      </div>

      <!-- Dimension line ----------------------------------------------------
           A span with a tick at each end, annotated at both: the action at the
           inline start, the count at the end. It measures the section the way a
           drawing measures an opening, and the plotter line runs along it. -->
      <div class="mt-12 sm:mt-14">
        <div class="dimension" aria-hidden="true">
          <span class="sweep-rail"></span>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-4 pt-5">
          <RouterLink
            to="/projects"
            class="group inline-flex items-center gap-3 text-label font-semibold text-ink transition-colors duration-base hover:text-gold-deep"
          >
            {{ $t('projects.view_all') }}
            <EaiIcon
              name="arrow-right"
              :size="16"
              flip
              class="transition-transform duration-base ease-out-quart group-hover:translate-x-1 rtl:group-hover:-translate-x-1"
            />
          </RouterLink>

          <!-- A real quantity, not a section index. It is the annotation a
               drawing carries at the end of a dimension. -->
          <p v-if="projects.length" class="text-label text-ink-subtle">
            <span class="font-semibold text-ink">{{ projects.length }}</span>
            <span class="mx-2 text-line-strong" aria-hidden="true">—</span>
            {{ $t('projects.selected_works') }}
          </p>
        </div>
      </div>

      <!-- Grid ---------------------------------------------------------------
           A rail on a phone, a grid from `sm`.

           Three portrait cards stacked is three screens of scrolling to learn
           that a practice has three featured projects. As a rail it is one
           gesture, the set reads as a set, and the card that peeks past the
           edge is what says there is more — which is the affordance a
           truncated column never gives you. -->
      <div
        v-if="projectStore.isLoading"
        class="rail-sm rail-bleed mt-10 gap-5 pb-2 sm:mt-14 sm:grid sm:grid-cols-2 sm:gap-x-8 sm:gap-y-12 lg:grid-cols-3"
      >
        <div v-for="i in 3" :key="i" class="w-[76%] animate-pulse sm:w-auto">
          <div class="arch w-full bg-canvas-inset [aspect-ratio:4/5] [--arch-rise:22%]"></div>
          <div class="mt-4 h-4 w-2/3 bg-canvas-inset"></div>
          <div class="mt-3 h-3 w-1/3 bg-canvas-inset"></div>
        </div>
      </div>

      <div
        v-else-if="projects.length"
        class="rail-sm rail-bleed mt-10 gap-5 pb-2 sm:mt-14 sm:grid sm:grid-cols-2 sm:gap-x-8 sm:gap-y-12 lg:grid-cols-3"
      >
        <ProjectCard
          v-for="project in projects"
          :key="project.id"
          :project="project"
          class="w-[76%] sm:w-auto"
        />
      </div>

      <p v-else class="mt-14 text-ink-subtle">{{ $t('projects.none_yet') }}</p>
    </div>
  </section>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useProjectStore } from '@/stores/projectStore'
import { useLocaleStore } from '@/stores/localeStore'
import ProjectCard from './ProjectCard.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'

const props = defineProps({
  content: { type: Object, default: () => ({}) },
})

const { t } = useI18n()
const projectStore = useProjectStore()
const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

const pick = (en, ar, key) =>
  computed(() => (isAr.value ? props.content?.[ar] : props.content?.[en]) || t(key))

// `title` is the section's name ("Featured Work") and `subtitle` is the
// sentence about it. Rendered the other way round, a twelve-word sentence
// became the h2 and the name became an eyebrow.
const heading = pick('title_en', 'title_ar', 'projects.title')
const lede = pick('subtitle_en', 'subtitle_ar', 'projects.subtitle')

const projects = computed(() => projectStore.featuredProjects.slice(0, 3))

onMounted(() => {
  projectStore.fetchProjects({ featured: 1 })
})
</script>
