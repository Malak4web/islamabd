<template>
  <svg
    ref="svg"
    :width="size"
    :height="size"
    viewBox="0 0 24 24"
    :class="['eai-icon', { 'eai-icon--flip': flip, 'eai-icon--draw': draw }]"
    :fill="isSolid ? 'currentColor' : 'none'"
    :stroke="isSolid ? 'none' : 'currentColor'"
    :stroke-width="isSolid ? undefined : stroke"
    stroke-linecap="square"
    stroke-linejoin="miter"
    :role="label ? 'img' : undefined"
    :aria-label="label || undefined"
    :aria-hidden="label ? undefined : 'true'"
    focusable="false"
  >
    <path v-for="(d, i) in paths" :key="i" :d="d" />
  </svg>
</template>

<script setup>
import { computed, ref, onMounted, watch, nextTick } from 'vue'
import { ICONS, BRAND, UI } from './icons.data.js'

/**
 * The single icon system for the public site.
 *
 * Replaces four systems that were shipping simultaneously: Heroicons paths
 * pasted inline, Lucide, Font Awesome loaded from a CDN, and raster PNGs
 * recoloured with a `brightness-0 invert` filter hack.
 *
 * DRAWING RULES — hold to these when adding an icon:
 *   · 24x24 grid, live area inset to 2px.
 *   · Strokes land on the half-pixel (x.5) so 1.25 hairlines stay crisp.
 *   · Square caps, mitred joins. This is a drafting convention and it is why
 *     these read as architectural rather than as generic UI icons — do not
 *     "soften" them to round.
 *   · No fills on line icons; colour arrives via `currentColor`, so an icon
 *     inherits whatever token its container sets. Never hard-code a colour.
 *
 * Brand marks (social) are the deliberate exception: they are trademarks,
 * users identify them by silhouette, so they keep their real geometry and are
 * filled rather than restyled to match.
 */

const props = defineProps({
  name: { type: String, required: true },
  size: { type: [Number, String], default: 20 },
  stroke: { type: [Number, String], default: 1.25 },
  /** Mirrors the icon under RTL. For directional glyphs (arrows), not symbols. */
  flip: { type: Boolean, default: false },
  /**
   * Lets the mark draw itself stroke by stroke when its container is revealed.
   * The animation is opt-in per usage because it belongs to the service marks —
   * an interface arrow that draws itself is a distraction, not a signature.
   */
  draw: { type: Boolean, default: false },
  /** Required when the icon is the only content of an interactive element. */
  label: { type: String, default: '' },
})

const svg = ref(null)

const isSolid = computed(() => props.name in BRAND)

const paths = computed(() => {
  const found = ICONS[props.name]
  if (found) return found
  // An unknown name must not punch an invisible hole where a mark belongs.
  if (import.meta.env?.DEV) console.warn(`[EaiIcon] unknown icon "${props.name}"`)
  return UI.building
})

/**
 * Each path is dashed to its own length so every stroke completes together.
 * A single shared dasharray would make the short strokes snap shut while the
 * long ones were still drawing, which reads as a glitch rather than a drawing.
 * `getTotalLength` is missing in jsdom, so the CSS carries a usable default.
 */
function measure() {
  const el = svg.value
  if (!props.draw || !el?.querySelectorAll) return
  for (const path of el.querySelectorAll('path')) {
    const length = typeof path.getTotalLength === 'function' ? path.getTotalLength() : 0
    if (length > 0) path.style.setProperty('--draw-len', String(Math.ceil(length)))
  }
}

onMounted(measure)
watch(() => props.name, () => nextTick(measure))
</script>

<style scoped>
.eai-icon {
  display: block;
  flex: none;
  vertical-align: middle;
}
/* The RTL mirror for .eai-icon--flip is declared globally in app.css —
   see the note on --dir-angle there for why it cannot live in a scoped block. */
</style>
