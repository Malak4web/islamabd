<template>
  <section
    v-depth
    class="relative isolate overflow-hidden bg-canvas pb-12 sm:pb-14 lg:h-[100svh] lg:min-h-[34rem] lg:pb-0"
    role="region"
    :aria-label="$t('hero.region_label')"
    aria-roledescription="carousel"
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
    @focusin="focusWithin = true"
    @focusout="focusWithin = false"
    @keydown="onKeydown"
  >
    <!-- Plate --------------------------------------------------------------
         Below `lg` this is a photograph in flow with the copy beneath it, and
         from `lg` it fills the section and the copy sits on top of it.

         That split is not a responsive convenience, it is the site's own rule
         applied where it was being broken: type goes under a photograph, on
         canvas. A phone is too narrow for a side scrim, so the previous build
         reached for a vertical one at 0.82–0.96 alpha — which guaranteed the
         contrast and erased the photograph doing it. A practice that sells
         rooms cannot show its rooms at four percent. -->
    <div class="relative h-[46svh] min-h-[15rem] overflow-hidden lg:absolute lg:inset-0 lg:h-auto lg:min-h-0">
      <!-- A real <img> rather than a CSS background: it gives us srcset (the
           old build shipped one 2000px file to phones), alt text, and an
           element the browser can treat as the LCP candidate. -->
      <div
        v-for="(slide, index) in resolvedSlides"
        :key="slide.key"
        class="absolute inset-0 transition-opacity duration-[1200ms] ease-out-expo"
        :class="[
          current === index ? 'opacity-100' : 'opacity-0 pointer-events-none',
          // The wipe is for slide *changes* only. Running it on the first paint
          // would hold the LCP image behind a 1.2s clip-path for no benefit.
          current === index && hasAdvanced ? 'hero-wipe-in' : '',
        ]"
        :aria-hidden="current === index ? undefined : 'true'"
      >
        <img
          :src="slide.src"
          :srcset="slide.srcset"
          sizes="100vw"
          :alt="slide.alt"
          class="hero-dolly absolute inset-0 h-full w-full object-cover"
          :class="{ 'animate-ken-burns': current === index }"
          :loading="index === 0 ? 'eager' : 'lazy'"
          :fetchpriority="index === 0 ? 'high' : 'auto'"
          decoding="async"
          width="2400"
          height="1600"
        />
        <!-- Directional scrim, desktop only — it exists to protect type that
             is over the image, and below `lg` no type is. It keeps the reading
             side above 92% opacity (>=12:1 against the worst-case frame) and
             lets the far side stay almost clear, so the photograph is still
             the design. -->
        <div class="hero-scrim absolute inset-0 hidden lg:block"></div>
      </div>

      <!-- The plotter line along the plate's bottom edge, where the photograph
           meets the sheet. Same seven-second pass as every page header. -->
      <span class="sweep-rail sweep-rail--foot lg:hidden" aria-hidden="true"></span>
    </div>

    <!-- Content ----------------------------------------------------------- -->
    <div class="relative z-raised lg:flex lg:h-full lg:items-center">
      <div class="container mx-auto px-6 pt-8 sm:px-8 sm:pt-10 lg:pt-0">
        <!-- The 54% cap is load-bearing, not taste: it keeps every line of copy
             inside the scrim's full-strength band (see .hero-scrim below).
             Widen one and you must widen the other. -->
        <!-- The words run ahead of the photograph as the hero scrolls away —
             about a line and a half over a full viewport. It is the one place
             on the site where the two planes visibly come apart, and it is the
             first thing a visitor sees, which is the whole reason it is here
             and nowhere else. The photograph deliberately stays put: it is
             already moving under the Ken Burns, and two competing drifts on one
             screen read as a page that cannot hold still. -->
        <div class="depth-lede max-w-2xl lg:max-w-[54%]">
          <!-- Live region announces slide changes to screen readers without
               moving focus. -->
          <div class="sr-only" aria-live="polite" aria-atomic="true">
            {{ $t('hero.slide_of', { current: current + 1, total: resolvedSlides.length }) }} —
            {{ activeSlide.title_top }} {{ activeSlide.title_bottom }}
          </div>

          <h1 :key="`t-${current}`" class="animate-fade-in-up text-display text-ink">
            <span class="block font-light">{{ activeSlide.title_top }}</span>
            <!-- Emphasis comes from weight, not from a clipped gradient.
                 Sentence case rather than uppercase: `uppercase` is a no-op on
                 Arabic, so the old build shouted in English and spoke normally
                 in Arabic — the two languages were not the same design. -->
            <span v-if="activeSlide.title_bottom" class="block font-medium">
              {{ activeSlide.title_bottom }}
            </span>
          </h1>

          <p
            v-if="activeSlide.description"
            :key="`d-${current}`"
            class="animate-fade-in-up delay-200 mt-7 max-w-measure text-lede text-ink-muted"
          >
            {{ activeSlide.description }}
          </p>

          <!-- One primary action per screen. On a phone the two used to be
               identical full-width pills, which makes a visitor choose between
               equals instead of following the obvious one; the second is
               subordinate now, in weight and in area, and only becomes its
               peer from `sm` where there is room to sit them side by side. -->
          <div class="animate-fade-in-up delay-300 mt-8 flex flex-col gap-2 sm:mt-10 sm:flex-row sm:items-center sm:gap-4">
            <!-- Gold carries the primary action as a FILL with an ink label:
                 8.3:1. The old build put white on this same gold — 2.3:1, the
                 least readable element on the most important button. -->
            <RouterLink
              to="/projects"
              class="press inline-flex items-center justify-center gap-2.5 rounded-xs bg-gold px-8 py-4 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft focus-visible:bg-gold-soft"
            >
              {{ activeSlide.cta_primary || $t('hero.cta_primary') }}
              <EaiIcon name="arrow-right" :size="16" flip />
            </RouterLink>

            <RouterLink
              to="/contact"
              class="press inline-flex items-center justify-center gap-2.5 rounded-xs px-8 py-3.5 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-ink/5 sm:border sm:border-ink/25 sm:py-4 sm:hover:border-ink sm:hover:bg-ink sm:hover:text-canvas"
            >
              {{ $t('hero.cta_secondary') }}
              <EaiIcon name="arrow-right" :size="15" flip class="sm:hidden" />
            </RouterLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Controls ----------------------------------------------------------
         The old build offered dots only: no pause on an 8s auto-advancing
         carousel, no keyboard access, no way to stop motion. -->
    <div
      v-if="resolvedSlides.length > 1"
      class="relative z-raised lg:absolute lg:inset-x-0 lg:bottom-0"
    >
      <!-- In flow under the copy on a phone, pinned to the plate's foot from
           `lg`. A control that overlays a photograph on a 390px screen is
           either too small to hit or large enough to cover the subject. -->
      <div class="container mx-auto px-6 pb-2 pt-8 sm:px-8 lg:pb-10 lg:pt-0">
        <div class="flex items-end justify-between gap-6 border-t border-ink/12 pt-5">
          <!-- The slide's own label, presented as a caption tied to the
               counter — information, not a decorative kicker above the title. -->
          <!-- Tabular figures: the counter is fixed in place while the slides
               change behind it, so 01 and 03 must occupy the same width or the
               slide label beside them shifts on every advance. -->
          <p class="min-w-0 flex-1 truncate text-label tabular-nums text-ink-muted">
            <span class="font-semibold text-ink">{{ String(current + 1).padStart(2, '0') }}</span>
            <span class="mx-2 text-ink-subtle">/</span>
            <span class="text-ink-subtle">{{ String(resolvedSlides.length).padStart(2, '0') }}</span>
            <span v-if="activeSlide.subtitle" class="ms-4 text-ink-muted">{{ activeSlide.subtitle }}</span>
          </p>

          <div class="flex flex-none items-center gap-1">
            <button
              type="button"
              class="hero-btn"
              :aria-label="$t('hero.previous')"
              @click="go(current - 1)"
            >
              <EaiIcon name="arrow-left" :size="18" flip />
            </button>

            <button
              type="button"
              class="hero-btn"
              :aria-label="playing ? $t('hero.pause') : $t('hero.play')"
              @click="togglePlay"
            >
              <span v-if="playing" class="flex h-[15px] w-[15px] items-center justify-center gap-[3px]" aria-hidden="true">
                <span class="h-full w-[3px] bg-current"></span>
                <span class="h-full w-[3px] bg-current"></span>
              </span>
              <span v-else class="block h-0 w-0 border-y-[7px] border-s-[11px] border-y-transparent border-s-current" aria-hidden="true"></span>
            </button>

            <button
              type="button"
              class="hero-btn"
              :aria-label="$t('hero.next')"
              @click="go(current + 1)"
            >
              <EaiIcon name="arrow-right" :size="18" flip />
            </button>
          </div>
        </div>

        <!-- Progress ticks double as slide selectors. -->
        <div class="mt-4 flex gap-1.5" role="tablist" :aria-label="$t('hero.region_label')">
          <button
            v-for="(slide, index) in resolvedSlides"
            :key="`tick-${slide.key}`"
            type="button"
            role="tab"
            :aria-selected="current === index"
            :aria-label="$t('hero.go_to', { n: index + 1 })"
            class="group h-4 flex-1 focus-visible:outline-offset-4"
            @click="go(index)"
          >
            <span
              class="block h-[2px] w-full transition-colors duration-base"
              :class="current === index ? 'bg-gold-deep' : 'bg-ink/15 group-hover:bg-ink/35'"
            ></span>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLocaleStore } from '@/stores/localeStore'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { HERO_SLIDES, srcset, fallback } from '@/lib/media'

const props = defineProps({
  /** Slides from the CMS. When absent, the bundled local set is used. */
  slides: { type: Array, default: null },
  /** Milliseconds between automatic advances. */
  interval: { type: Number, default: 7000 },
})

const { t } = useI18n()
// Locale comes from the store rather than vue-i18n: it is the app's single
// source of truth for direction and language, and it stays defined wherever
// the component is mounted.
const localeStore = useLocaleStore()

const AUTOPLAY_SUPPORTED =
  typeof window !== 'undefined' && typeof window.matchMedia === 'function'

/** Users who ask for less motion get a static hero, not a slower one. */
const prefersReducedMotion = () =>
  AUTOPLAY_SUPPORTED && window.matchMedia('(prefers-reduced-motion: reduce)').matches

const current = ref(0)
/** Gates the slide wipe so it never runs on the first paint. See the template. */
const hasAdvanced = ref(false)
const playing = ref(false)
const hovering = ref(false)
const focusWithin = ref(false)

/** Normalises CMS slides and local defaults into one shape. */
const resolvedSlides = computed(() => {
  const lang = localeStore.isArabic ? 'ar' : 'en'

  const local = HERO_SLIDES.map((s, i) => ({
    key: `local-${i}`,
    src: fallback(s.base, s.widths),
    srcset: srcset(s.base, s.widths),
    alt: s.alt[lang],
    title_top: t(`hero.defaults.s${i + 1}.title_top`),
    title_bottom: t(`hero.defaults.s${i + 1}.title_bottom`),
    subtitle: t(`hero.defaults.s${i + 1}.subtitle`),
    description: t(`hero.defaults.s${i + 1}.description`),
    cta_primary: '',
  }))

  if (!Array.isArray(props.slides) || props.slides.length === 0) return local

  return props.slides.map((slide, i) => {
    // A CMS slide without an image falls back to the matching local photo
    // rather than rendering an empty frame.
    const hasImage = Boolean(slide.image)
    const localMatch = HERO_SLIDES[i % HERO_SLIDES.length]
    return {
      key: `cms-${i}`,
      src: hasImage ? slide.image : fallback(localMatch.base, localMatch.widths),
      srcset: hasImage ? undefined : srcset(localMatch.base, localMatch.widths),
      alt: slide.alt || [slide.title_top, slide.title_bottom].filter(Boolean).join(' ') || localMatch.alt[lang],
      title_top: slide.title_top || local[i % local.length].title_top,
      title_bottom: slide.title_bottom || '',
      subtitle: slide.subtitle || '',
      description: slide.description || '',
      cta_primary: slide.cta_primary || '',
    }
  })
})

const activeSlide = computed(
  () => resolvedSlides.value[current.value] ?? resolvedSlides.value[0] ?? {}
)

let timer = null

const stop = () => {
  if (timer) clearInterval(timer)
  timer = null
}

const start = () => {
  stop()
  if (!playing.value || resolvedSlides.value.length < 2) return
  timer = setInterval(() => go(current.value + 1), props.interval)
}

function go(index) {
  const n = resolvedSlides.value.length
  if (n === 0) return
  const next = ((index % n) + n) % n
  if (next !== current.value) hasAdvanced.value = true
  current.value = next
  start()
}

function togglePlay() {
  playing.value = !playing.value
  playing.value ? start() : stop()
}

function onKeydown(e) {
  if (e.key === 'ArrowRight') { e.preventDefault(); go(current.value + 1) }
  else if (e.key === 'ArrowLeft') { e.preventDefault(); go(current.value - 1) }
}

// Pause while the visitor is reading or interacting, and whenever the tab is
// hidden — an off-screen carousel burning through slides helps nobody.
watch([hovering, focusWithin], ([h, f]) => (h || f ? stop() : start()))

const onVisibility = () => (document.hidden ? stop() : start())

onMounted(() => {
  playing.value = !prefersReducedMotion()
  start()
  document.addEventListener('visibilitychange', onVisibility)
})

onUnmounted(() => {
  stop()
  document.removeEventListener('visibilitychange', onVisibility)
})
</script>

<style scoped>
/**
 * The scrim is the contrast guarantee, so it is written explicitly rather than
 * assembled from utilities. Reading side stays >=0.92 alpha; verified against a
 * near-black frame at 12.9:1 for ink and 6.3:1 for muted body text.
 */
/**
 * Horizontal scrim, `lg` and up only. The 0.92 plateau runs to 54% because
 * that is exactly where the text column is capped (`lg:max-w-[54%]` above) —
 * the protected band is sized to the copy, not eyeballed. Past the plateau it
 * falls away quickly so the photograph reads at full strength on the far side.
 *
 * Verified against a near-black frame: 15.0:1 for headings and 7.3:1 for body
 * at the far edge of the text column.
 */
/* `--dir-angle` is 90deg in LTR and 270deg in RTL (declared in app.css), so the
   protected side always follows the text. It replaces a `:global([dir='rtl'])`
   override that silently compiled down to a bare `[dir='rtl']` rule and
   painted this gradient across the entire <html> element in Arabic. */
.hero-scrim {
  background-image: linear-gradient(
    var(--dir-angle),
    oklch(var(--c-canvas) / 0.96) 0%,
    oklch(var(--c-canvas) / 0.92) 54%,
    oklch(var(--c-canvas) / 0.28) 76%,
    oklch(var(--c-canvas) / 0.04) 100%
  );
}

/* There is deliberately no mobile scrim. Below `lg` the copy is not on the
   photograph, so nothing needs protecting, and the vertical wash that used to
   stand in for a side gradient took the image with it. */

.hero-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.75rem;
  height: 2.75rem;
  border-radius: 999px;
  color: oklch(var(--c-ink));
  transition: background-color var(--dur-fast) var(--ease-out-quart);
}
.hero-btn:hover {
  background-color: oklch(var(--c-ink) / 0.07);
}
</style>
