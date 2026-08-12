<template>
  <!-- The page closes on ink. Inverting here does two jobs: it gives the long
       light scroll a hard stop, and it is the one place the brand gold can sit
       at full strength and still be legible (8.3:1 on ink, versus 2.1:1 on the
       canvas). The old banner was a 3rem-radius panel with blurred decorative
       circles behind it — decoration standing in for a reason to act. -->
  <section class="relative isolate overflow-hidden bg-ink">
    <!-- A setting-out grid, gold at 0.08 on ink, drifting continuously by
         exactly one module so the loop never visibly jumps. The old banner
         reached for blurred colour circles here; a drafting grid says the same
         thing about the practice and says it in the practice's own language.
         Inset negative so the drift never exposes an edge. -->
    <div
      class="grid-draft grid-draft--drift absolute -inset-24 -z-10"
      aria-hidden="true"
    ></div>

    <!-- And on that sheet, the drawing. Every page on this site closes here, so
         this is the last thing a visitor sees before they decide whether to
         write — and what it says is that the practice thinks in three
         dimensions and draws before it builds. It is the same gold hairline and
         the same 350mm module as the grid behind it, one dimension deeper. -->
    <DraftingRoom class="absolute inset-0 -z-10" />
    <!-- The drawing sits toward the trailing edge and this hands the reading
         side back to the type — the same directional device as the hero scrim,
         for the same reason. The room keeps full strength in the negative
         space, where it is the point. -->
    <div class="cta-veil absolute inset-0 -z-10" aria-hidden="true"></div>

    <div class="container mx-auto px-6 py-20 sm:px-8 sm:py-28">
      <div class="grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:items-end lg:gap-16">
        <div>
          <span v-reveal:rule class="block h-px w-16 bg-gold" aria-hidden="true"></span>
          <h2 v-reveal class="mt-7 text-title font-light text-canvas">
            {{ title }}
          </h2>
          <p v-if="body" v-reveal="{ delay: 90 }" class="mt-5 max-w-prose text-lede text-canvas/70">
            {{ body }}
          </p>
        </div>

        <div v-reveal="{ delay: 150 }" class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch">
          <RouterLink
            :to="primaryLink"
            class="press inline-flex items-center justify-center gap-2.5 rounded-xs bg-gold px-8 py-4 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft"
          >
            {{ primaryLabel }}
            <EaiIcon name="arrow-right" :size="16" flip />
          </RouterLink>

          <!-- On the device most of this audience is holding, "call us" is a
               one-tap action, not a number to memorise. -->
          <a
            v-if="phone"
            :href="`tel:${phone}`"
            class="press inline-flex items-center justify-center gap-2.5 rounded-xs border border-canvas/30 px-8 py-4 text-label font-semibold tracking-wide text-canvas transition duration-base ease-out-quart hover:border-canvas hover:bg-canvas hover:text-ink"
          >
            <EaiIcon name="phone" :size="16" />
            {{ $t('cta.secondary') }}
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useSettingStore } from '@/stores/settingStore'
import { useLocaleStore } from '@/stores/localeStore'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import DraftingRoom from './DraftingRoom.vue'

const props = defineProps({
  content: { type: Object, default: () => ({}) },
})

const { t } = useI18n()
const settingStore = useSettingStore()
const localeStore = useLocaleStore()

const isAr = computed(() => localeStore.isArabic)
const pick = (en, ar, fallbackKey) =>
  computed(() => (isAr.value ? props.content?.[ar] : props.content?.[en]) || t(fallbackKey))

const title = pick('title_en', 'title_ar', 'cta.title')
const body = pick('body_en', 'body_ar', 'cta.subtitle')
const primaryLabel = pick('cta_en', 'cta_ar', 'cta.primary')
const primaryLink = computed(() => props.content?.cta_link || '/services')

const phone = computed(
  () => settingStore.settings.phone_main || settingStore.settings.contact_phone_eg || ''
)
</script>

<style scoped>
/* `--dir-angle` is 90deg in LTR and 270deg in RTL, published once in app.css.
   Writing the flip here as a `:global([dir='rtl'])` override is the trap
   documented on that variable — the scoped compiler drops the descendant and
   paints the gradient across <html>. */
.cta-veil {
  background-image: linear-gradient(
    var(--dir-angle),
    oklch(var(--c-ink) / 0.82) 0%,
    oklch(var(--c-ink) / 0.62) 42%,
    oklch(var(--c-ink) / 0) 78%
  );
}

/* On a phone there is no negative space to move the drawing into — the copy and
   the room occupy the same column whatever the offset does — so the veil keeps
   a floor across the full width instead of clearing on the far side. The room
   is still there at about a third strength, as texture rather than as a
   drawing, and the body copy gets a clean ground. Legibility wins the screens
   where the two cannot be separated. */
@media (max-width: 639px) {
  .cta-veil {
    background-image: linear-gradient(
      var(--dir-angle),
      oklch(var(--c-ink) / 0.86) 0%,
      oklch(var(--c-ink) / 0.74) 55%,
      oklch(var(--c-ink) / 0.52) 100%
    );
  }
}
</style>
