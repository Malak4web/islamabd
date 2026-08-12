<template>
  <div v-if="pageStore.isLoading" class="flex min-h-[62svh] items-center justify-center bg-canvas">
    <span class="sr-only">{{ $t('common.loading') }}</span>
    <div
      class="h-10 w-10 animate-spin rounded-pill border-2 border-line border-t-gold-deep"
      role="status"
    ></div>
  </div>

  <main v-else class="bg-canvas">
    <!-- The old header set the studio's name in animated gradient-clipped text
         over a Ken Burns background. Gradient text is decorative at the cost of
         legibility, and it was applied to the single most important string on
         the page. -->
    <PageHeader
      :title="headerTitle"
      :lede="headerLede"
      :image="banner"
      :image-alt="banner.alt[isAr ? 'ar' : 'en']"
    >
      <template #title>
        {{ headerTitle }}
        <!-- Only when the CMS title does not already carry the name. It reads
             "About Eslam Abdulghani Designs" by default, and appending the
             studio name to that printed it twice. -->
        <span v-if="!titleNamesStudio" class="mt-2 block font-medium text-ink">
          Eslam Abdulghani Designs
        </span>
      </template>
    </PageHeader>

    <!-- Sections from the CMS -->
    <div v-for="(section, index) in contentSections" :key="section.id">
      <!-- Story / Mission: alternating image and copy -->
      <section
        v-if="section.key === 'story' || section.key === 'mission'"
        class="py-16 sm:py-24 lg:py-32"
        :class="index % 2 === 0 ? 'bg-canvas' : 'bg-canvas-raised'"
      >
        <div class="container mx-auto px-6 sm:px-8">
          <!-- `items-stretch`, not `items-center`: it is what lets the figure
               take its height from the paragraph beside it. -->
          <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
            <div
              class="lg:flex lg:flex-col lg:justify-center"
              :class="index % 2 !== 0 ? 'lg:order-2' : ''"
            >
              <span v-reveal:rule class="block h-px w-14 bg-gold" aria-hidden="true"></span>
              <p v-reveal class="mt-6 text-label font-semibold text-gold-deep">
                {{ $t(`about.${section.key}`) }}
              </p>
              <h2 v-reveal="{ delay: 60 }" class="mt-3 text-heading font-light text-ink">
                {{ localised(section.content, 'title') }}
              </h2>
              <p v-reveal="{ delay: 120 }" class="mt-6 max-w-prose whitespace-pre-line text-lede text-ink-muted">
                {{ localised(section.content, 'body') }}
              </p>

              <RouterLink
                v-if="localised(section.content, 'cta')"
                to="/contact"
                class="group mt-8 inline-flex items-center gap-3 text-label font-semibold text-ink transition-colors duration-base hover:text-gold-deep"
              >
                {{ localised(section.content, 'cta') }}
                <EaiIcon
                  name="arrow-right"
                  :size="16"
                  flip
                  class="transition-transform duration-base ease-out-quart group-hover:translate-x-1 rtl:group-hover:-translate-x-1"
                />
              </RouterLink>
            </div>

            <!-- Each section gets its own pair. Every one of these used to
                 resolve to the same fallback at the same fixed ratio, so the
                 page showed one frame three times and none of them matched the
                 paragraph beside it. -->
            <div class="flex items-center justify-center" :class="index % 2 !== 0 ? 'lg:order-1' : ''">
              <AboutFigure
                :image="sectionImage(section.key)"
                :cms-image="section.content.image"
                :main-alt-override="localised(section.content, 'title')"
                :arabic="isAr"
              />
            </div>
          </div>
        </div>
      </section>

      <!-- Expertise -->
      <section v-else-if="section.key === 'expertise'" class="border-t border-line py-16 sm:py-24 lg:py-32">
        <div class="container mx-auto px-6 sm:px-8">
          <div class="max-w-3xl">
            <span v-reveal:rule class="block h-px w-14 bg-gold" aria-hidden="true"></span>
            <p v-reveal class="mt-6 text-label font-semibold text-gold-deep">{{ $t('about.capabilities') }}</p>
            <h2 v-reveal="{ delay: 60 }" class="mt-3 text-heading font-light text-ink">
              {{ localised(section.content, 'title') }}
            </h2>
          </div>

          <div class="mt-12 grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
              <p class="max-w-prose text-lede text-ink-muted">
                {{ localised(section.content, 'body') }}
              </p>

              <!-- The four capability marks draw themselves in sequence: these
                   are architectural drawings, so they arrive the way a drawing
                   does. Staggered by position, not all at once. -->
              <ul class="mt-10 grid gap-x-8 gap-y-8 sm:grid-cols-2">
                <li
                  v-for="(skill, i) in SKILLS"
                  :key="skill.key"
                  v-reveal="{ delay: i * 110 }"
                  class="border-t border-line pt-5"
                >
                  <EaiIcon :name="skill.icon" :size="26" draw class="text-gold-deep" />
                  <h3 class="mt-4 text-subhead font-medium text-ink">
                    {{ $t(`about.${skill.key}`) }}
                  </h3>
                </li>
              </ul>
            </div>

            <!-- Was greyscaled, then tinted with a gold `mix-blend-overlay`
                 layer. Two filters fighting each other over a photograph the
                 studio chose deliberately. -->
            <div class="lg:col-span-5">
              <AboutFigure
                :image="sectionImage('expertise')"
                :cms-image="section.content.image"
                :main-alt-override="localised(section.content, 'title')"
                :arabic="isAr"
                sizes="(min-width: 1024px) 42vw, 100vw"
              />
            </div>
          </div>
        </div>
      </section>
    </div>

    <section v-if="!contentSections.length" class="py-24 text-center">
      <p class="text-ink-subtle">{{ $t('about.coming_soon') }}</p>
    </section>

    <MarkTicker />

    <CtaBanner />
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'

import PageHeader from '@/components/public/PageHeader.vue'
import MarkTicker from '@/components/public/MarkTicker.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'
import AboutFigure from '@/components/public/AboutFigure.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { ABOUT_SECTION_IMAGES, PAGE_BANNERS } from '@/lib/media'

const { t } = useI18n()
const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const isAr = computed(() => localeStore.isArabic)

const banner = PAGE_BANNERS.about

/** Picks `<field>_ar` or `<field>_en` off a CMS content blob. */
const localised = (content, field) =>
  (isAr.value ? content?.[`${field}_ar`] : content?.[`${field}_en`]) || ''

const SKILLS = [
  { key: 'construction', icon: 'industrial' },
  { key: 'interior', icon: 'residential' },
  { key: 'landscape', icon: 'landscape' },
  { key: 'architectural', icon: 'exterior' },
]

const heroSection = computed(() =>
  pageStore.currentPage?.sections?.find((s) => s.key === 'hero')
)

const contentSections = computed(
  () => pageStore.currentPage?.sections?.filter((s) => s.key !== 'hero') ?? []
)

const headerTitle = computed(() => localised(heroSection.value?.content, 'title') || t('about.title'))
const headerLede = computed(
  () => localised(heroSection.value?.content, 'subtitle') || t('about.legacy')
)

const titleNamesStudio = computed(() =>
  /abdulghani|عبد ?الغني/i.test(headerTitle.value)
)

/**
 * The `{ main, detail }` pair for a section. Locally hosted, so a section with
 * no image never reaches for a third-party URL and no two sections show the
 * same frame. Unknown keys fall back to `story` rather than rendering an empty
 * well.
 */
const sectionImage = (key) => ABOUT_SECTION_IMAGES[key] ?? ABOUT_SECTION_IMAGES.story

useSeo(currentPage)

onMounted(() => {
  pageStore.fetchPage('about')
})
</script>
