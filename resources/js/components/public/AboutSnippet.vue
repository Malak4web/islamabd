<template>
  <section class="bg-canvas py-20 sm:py-28 lg:py-36">
    <div class="container mx-auto px-6 sm:px-8">
      <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
        <!-- Image ---------------------------------------------------------
             Square-cornered and unadorned. The old treatment wrapped it in a
             3xl radius, a 2xl "15+ YEARS OF EXCELLENCE" card floating off the
             corner, and a gradient veil — three decorations competing with the
             photograph they were decorating. -->
        <!-- The arch, with a second one struck behind it as an outline — the
             drawn opening and the built one, offset. It is the practice's own
             subject matter used as its identity, and it is the reason this
             block reads as an interiors studio rather than as a stock photo in
             a box. -->
        <figure class="relative">
          <!-- Logical insets only. Pairing these with an `isArabic` conditional
               flips them twice and lands the outline back where it started. -->
          <!-- The two arches now sit on different planes and separate as the
               section scrolls: the drawn opening lags, the built one runs with
               the photograph inside it. It is the same offset the flat version
               had, made literal. -->
          <span
            v-depth
            class="arch depth-plane pointer-events-none absolute -top-8 bottom-14 start-14 hidden border border-gold/55 lg:block [inset-inline-end:-2rem] [--arch-rise:22%]"
            aria-hidden="true"
          ></span>

          <div
            v-depth
            v-tilt="{ max: 3 }"
            v-reveal:wipe
            class="arch group set-plate relative overflow-hidden bg-canvas-inset [--arch-rise:22%] [--set-max:3deg] [--set-z:-14px]"
          >
            <img
              :src="aboutImage.src"
              :srcset="aboutImage.srcset"
              sizes="(min-width: 1024px) 50vw, 100vw"
              :alt="aboutImage.alt"
              loading="lazy"
              decoding="async"
              width="1400"
              height="1050"
              class="plate-img aspect-[4/3] w-full object-cover lg:aspect-[4/5]"
            />
            <span class="sheen" aria-hidden="true"></span>
          </div>
        </figure>

        <!-- Copy ---------------------------------------------------------- -->
        <div>
          <span v-reveal:rule class="block h-px w-14 bg-gold" aria-hidden="true"></span>

          <h2 v-reveal class="mt-7 text-title font-light text-ink">
            {{ title }}
          </h2>

          <p v-reveal="{ delay: 90 }" class="mt-6 max-w-prose text-lede text-ink-muted">
            {{ body }}
          </p>

          <!-- Practice facts as a plain definition list. Previously these were
               four black-weight numerals over wide-tracked gold labels — the
               hero-metric block every SaaS landing page ships. The numbers are
               the same; they no longer pretend to be the headline. -->
          <!-- The figures are set in the display face, light, at heading size,
               over their labels in the small annotating one. Same two voices as
               the rest of the page — a number stated in the practice's own
               typography stops being a statistic and becomes a fact it is
               proud of. `tabular-nums` keeps the four of them on a common
               vertical, which is the whole reason they are worth setting
               large. -->
          <dl class="mt-10 grid grid-cols-2 gap-x-8 gap-y-7 border-t border-line pt-8 sm:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
            <div v-for="(stat, i) in stats" :key="stat.key" v-reveal="{ delay: 140 + i * 80 }">
              <dt class="text-label text-ink-subtle">{{ $t(`about.stats.${stat.key}`) }}</dt>
              <dd class="mt-1 font-display text-heading font-light tabular-nums text-ink">
                {{ stat.value }}
              </dd>
            </div>
          </dl>

          <RouterLink
            to="/about"
            class="group mt-10 inline-flex items-center gap-3 text-label font-semibold text-ink transition-colors duration-base hover:text-gold-deep"
          >
            {{ ctaLabel }}
            <EaiIcon
              name="arrow-right"
              :size="16"
              flip
              class="transition-transform duration-base ease-out-quart group-hover:translate-x-1 rtl:group-hover:-translate-x-1"
            />
          </RouterLink>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLocaleStore } from '@/stores/localeStore'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { ABOUT_IMAGE, srcset, fallback } from '@/lib/media'

const props = defineProps({
  content: { type: Object, default: () => ({}) },
})

const { t } = useI18n()
const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

const pick = (en, ar, key) =>
  computed(() => (isAr.value ? props.content?.[ar] : props.content?.[en]) || t(key))

const title = pick('title_en', 'title_ar', 'about.title')
const body = pick('text_en', 'text_ar', 'about.body')
const ctaLabel = pick('cta_en', 'cta_ar', 'about.cta')

const aboutImage = computed(() => {
  const lang = isAr.value ? 'ar' : 'en'
  if (props.content?.image) {
    return { src: props.content.image, srcset: undefined, alt: ABOUT_IMAGE.alt[lang] }
  }
  return {
    src: fallback(ABOUT_IMAGE.base, ABOUT_IMAGE.widths),
    srcset: srcset(ABOUT_IMAGE.base, ABOUT_IMAGE.widths),
    alt: ABOUT_IMAGE.alt[lang],
  }
})

const stats = [
  { key: 'projects', value: '250+' },
  { key: 'clients', value: '180+' },
  { key: 'awards', value: '45' },
  { key: 'architects', value: '30+' },
]
</script>
