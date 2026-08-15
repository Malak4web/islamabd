<template>
  <section class="bg-canvas-raised py-20 sm:py-28 lg:py-32">
    <!-- Section head ------------------------------------------------------
         The heading was carrying the twelve-word descriptor at display size
         while the section's actual name — "Our Services" — sat underneath as a
         paragraph. A sentence is not a heading. The name leads now, the
         descriptor is the lede, and the eight lines are set out as an index
         beside them: a reader learns what this practice covers before a single
         card has loaded. -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid gap-8 sm:gap-10 border-b border-line pb-10 sm:pb-12 lg:grid-cols-12 lg:gap-16">
        <div class="lg:col-span-6">
          <span v-reveal:rule class="block h-px w-12 sm:w-14 bg-gold" aria-hidden="true"></span>
          <h2 v-reveal class="mt-5 sm:mt-7 text-xl sm:text-2xl lg:text-title font-light text-ink">{{ heading }}</h2>
          <p v-if="lede" v-reveal="{ delay: 90 }" class="mt-4 sm:mt-5 max-w-prose text-sm sm:text-base lg:text-lede text-ink-muted">
            {{ lede }}
          </p>

          <RouterLink
            v-reveal="{ delay: 150 }"
            to="/services"
            class="group mt-7 sm:mt-9 inline-flex items-center gap-2 sm:gap-3 text-xs sm:text-label font-semibold text-ink transition-colors duration-base hover:text-gold-deep"
          >
            {{ $t('services.view_all') }}
            <EaiIcon
              name="arrow-right"
              :size="16"
              flip
              class="transition-transform duration-base ease-out-quart group-hover:translate-x-1 rtl:group-hover:-translate-x-1"
            />
          </RouterLink>
        </div>

        <!-- Service lines index — 2-col grid on all screens, compact cards on mobile -->
        <ul class="grid grid-cols-2 gap-2 sm:gap-x-8 sm:gap-y-0 lg:col-span-6 lg:col-start-7">
          <li
            v-for="(line, i) in LINES"
            :key="line"
            v-reveal="{ delay: 60 + i * 55 }"
            class="sm:border-t sm:border-line"
          >
            <RouterLink
              :to="{ path: '/services', hash: `#${line}` }"
              class="group flex items-center gap-3 sm:gap-4 rounded-xl sm:rounded-none border border-line sm:border-0 bg-canvas sm:bg-transparent p-3 sm:py-4 sm:px-0 transition-all duration-200 active:scale-[0.97] sm:active:scale-100 focus-visible:outline-offset-4 hover:border-gold/40 sm:hover:border-transparent"
            >
              <span class="flex h-10 w-10 sm:h-auto sm:w-auto items-center justify-center rounded-lg sm:rounded-none bg-canvas-raised sm:bg-transparent flex-shrink-0">
                <EaiIcon
                  :name="line"
                  :size="22"
                  draw
                  class="text-gold-deep transition-colors duration-base group-hover:text-ink"
                />
              </span>
              <span class="min-w-0 flex-1 truncate text-xs sm:text-label font-medium text-ink-muted transition-colors duration-base group-hover:text-ink">
                {{ $t(`services.lines.${line}`) }}
              </span>
              <EaiIcon
                name="arrow-right"
                :size="12"
                flip
                class="flex-none text-line-strong opacity-0 transition-all duration-base ease-out-quart group-hover:translate-x-1 group-hover:text-gold-deep group-hover:opacity-100 rtl:group-hover:-translate-x-1 sm:block hidden"
              />
            </RouterLink>
          </li>
        </ul>
      </div>
    </div>

    <!-- The drafting strip. Perpetual, one direction, full bleed — the one
         place on the page where the practice's marks are simply always moving. -->
    <MarkTicker class="mt-14 sm:mt-16" />

    <!-- Grid -------------------------------------------------------------- -->
    <div class="container mx-auto px-6 sm:px-8">
      <!-- A rail on a phone, a grid from `sm` — see ProjectsPreview. Six cards
           in a column is six screens; six in a rail is one gesture. -->
      <div
        v-if="serviceStore.isLoading"
        class="rail-sm rail-bleed mt-10 gap-5 pb-2 sm:mt-14 sm:grid sm:grid-cols-2 sm:gap-x-8 sm:gap-y-12 lg:grid-cols-3"
      >
        <div v-for="i in 3" :key="i" class="w-[80%] animate-pulse sm:w-auto">
          <div class="arch aspect-[4/5] w-full bg-canvas-inset [--arch-rise:22%]"></div>
          <div class="mt-6 h-4 w-2/3 bg-canvas-inset"></div>
          <div class="mt-3 h-3 w-full bg-canvas-inset"></div>
        </div>
      </div>

      <div
        v-else-if="services.length"
        class="rail-sm rail-bleed mt-10 gap-5 pb-2 sm:mt-14 sm:grid sm:grid-cols-2 sm:gap-x-8 sm:gap-y-12 lg:grid-cols-3"
      >
        <ServiceCard
          v-for="service in services"
          :key="service.id"
          :service="service"
          class="w-[80%] sm:w-auto"
        />
      </div>

      <p v-else class="mt-14 text-ink-subtle">{{ $t('services.none_yet') }}</p>
    </div>
  </section>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useServiceStore } from '@/stores/serviceStore'
import { useLocaleStore } from '@/stores/localeStore'
import ServiceCard from './ServiceCard.vue'
import MarkTicker from './MarkTicker.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'

const props = defineProps({
  content: { type: Object, default: () => ({}) },
})

const { t } = useI18n()
const serviceStore = useServiceStore()
const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

const pick = (en, ar, key) =>
  computed(() => (isAr.value ? props.content?.[ar] : props.content?.[en]) || t(key))

// `title` is the section's name ("Our Services") and `subtitle` is the twelve-
// word descriptor. They were rendered the other way round, which set a sentence
// at display size and demoted the name to body copy.
const heading = pick('title_en', 'title_ar', 'services.title')
const lede = pick('subtitle_en', 'subtitle_ar', 'services.subtitle')

const LINES = [
  'administrative',
  'commercial',
  'residential',
  'exterior',
  'hospitality',
  'landscape',
  'retail',
  'industrial',
]

const services = computed(() => serviceStore.services.slice(0, 6))

onMounted(() => {
  if (!serviceStore.services.length) serviceStore.fetchServices()
})
</script>
