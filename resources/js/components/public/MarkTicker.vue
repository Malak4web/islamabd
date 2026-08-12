<template>
  <!-- A drafting strip: the eight service marks and their names travelling
       continuously with the reading direction. This is the site's one piece of
       ambient motion at content scale — it says "this practice draws" without
       any copy, and it never stops, so the page reads as alive rather than as
       something that animated once on scroll and went still.

       aria-hidden because it is a decorative restatement of the service names
       that are already linked, in order, in the grid directly below it. A
       screen reader gaining eight unlabelled repeats of the same list would be
       noise, not information. -->
  <div
    class="marquee-mask relative overflow-hidden border-y border-line bg-canvas py-5 select-none"
    aria-hidden="true"
  >
    <div class="marquee-track" :style="{ '--marquee-dur': `${duration}s` }">
      <!-- The track holds the list twice; the keyframe translates by exactly
           half the track, which lands on an identical frame. -->
      <div v-for="pass in 2" :key="pass" class="flex shrink-0 items-center">
        <div
          v-for="line in LINES"
          :key="`${pass}-${line.key}`"
          class="flex shrink-0 items-center gap-3 px-7 sm:gap-4 sm:px-9"
        >
          <EaiIcon :name="line.key" :size="26" class="text-gold-deep" />
          <span class="whitespace-nowrap text-label font-medium tracking-wide text-ink-muted">
            {{ $t(`services.lines.${line.key}`) }}
          </span>
          <span class="ms-4 block h-4 w-px bg-line sm:ms-5" aria-hidden="true"></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import EaiIcon from '@/components/icons/EaiIcon.vue'

defineProps({
  /** Seconds for one full pass. Slow on purpose — this is ambience, not a feed. */
  duration: { type: Number, default: 48 },
})

// The eight service lines, in the order the practice lists them. Names come
// from i18n rather than the API so the strip renders identically on every page
// and never waits on a request.
const LINES = [
  { key: 'administrative' },
  { key: 'commercial' },
  { key: 'residential' },
  { key: 'exterior' },
  { key: 'hospitality' },
  { key: 'landscape' },
  { key: 'retail' },
  { key: 'industrial' },
]
</script>
