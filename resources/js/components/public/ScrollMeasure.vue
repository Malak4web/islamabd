<template>
  <div ref="root" class="measure z-sticky hidden lg:block" aria-hidden="true">
    <!-- Decorative. The scrollbar is the real affordance and this duplicates no
         information a visitor could otherwise miss, so it stays out of the
         accessibility tree entirely. The comment lives INSIDE the root: a
         sibling comment makes the component multi-root, which drops attribute
         fallthrough and hands `wrapper.element` a fragment placeholder. -->
    <span class="measure__rail"></span>
    <span class="measure__run"></span>
    <span class="measure__witness measure__witness--head"></span>
    <span class="measure__witness measure__witness--foot"></span>
    <span class="measure__station"></span>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { addFrameReader, clamp } from '@/lib/motion'

/**
 * The page, measured.
 *
 * Every site draws a scroll indicator as a coloured bar filling up. This one is
 * a dimension line: a hairline with a witness tick at each end marking the
 * extent of the document, and a travelling station tick showing where in that
 * span you are. It is the same annotation the drafting room carries and the
 * same one `.dimension` uses in the page body — the site measures its own
 * length in the notation it measures rooms in.
 *
 * Desktop only. On a phone the tab bar owns the bottom edge, the gutters are a
 * few millimetres wide, and a fixed rail down the side is one more thing
 * competing for a screen that has none to spare.
 */

const root = ref(null)

let off = null
let resizeObserver = null
/** Pixels the station can travel. Measured, not guessed — see the note below. */
let span = 0

const measure = () => {
  const el = root.value
  if (!el) return
  span = Math.max(0, el.clientHeight - 2)
  el.style.setProperty('--span', `${span}px`)
}

const read = () => {
  const el = root.value
  if (!el) return
  const doc = document.documentElement
  const travel = doc.scrollHeight - doc.clientHeight
  // A page shorter than the viewport has no span to measure; parking the value
  // at 0 keeps the station on the head tick rather than dividing by zero and
  // landing somewhere undefined.
  const run = travel > 8 ? clamp((window.scrollY || doc.scrollTop || 0) / travel, 0, 1) : 0
  el.style.setProperty('--run', run.toFixed(4))
}

onMounted(() => {
  measure()
  read()
  off = addFrameReader(read)

  // The travel has to be a LENGTH, because the station moves on `transform` and
  // a percentage there resolves against the tick's own 2px height, not the
  // rail's. Animating `top` instead would put a layout property on the frame
  // loop. So the rail's height is measured once and republished on resize.
  if (typeof ResizeObserver === 'function') {
    resizeObserver = new ResizeObserver(measure)
    resizeObserver.observe(root.value)
  }
})

onUnmounted(() => {
  off?.()
  off = null
  resizeObserver?.disconnect()
})
</script>

<style scoped>
/* Logical insets throughout, so the line runs down the leading gutter in both
   languages with no direction code.

   0.875rem is measured against the tightest case, not chosen: between 1024 and
   1280 the container runs full width with 2rem of padding, so content starts at
   32px and this occupies 14–23px. Any further out and it crowds the first
   column at exactly the width where there is least room. */
.measure {
  position: fixed;
  inset-inline-start: 0.875rem;
  top: 8.5rem;
  bottom: 5rem;
  width: 9px;
  pointer-events: none;
}

.measure__rail,
.measure__run {
  position: absolute;
  inset-inline-start: 4px;
  top: 0;
  width: 1px;
  height: 100%;
}
/* Gold, not ink. The measure is fixed to the viewport and the page scrolls
   under it — including a full-bleed ink section and full-bleed photographs. An
   ink hairline vanishes on all three. Gold at 74.7% lightness is the one value
   in this palette that separates from both the canvas and the ink. */
.measure__rail {
  background-color: oklch(var(--c-gold) / 0.34);
}
/* Scales from the top rather than animating `height`: one is a composited
   transform, the other is a layout property changing sixty times a second. */
.measure__run {
  transform-origin: 50% 0;
  transform: scaleY(var(--run, 0));
  background-color: oklch(var(--c-gold) / 0.8);
}

/* The witness ticks: the extent being measured. Without them the rail is a
   progress bar; with them it is a dimension. */
.measure__witness {
  position: absolute;
  inset-inline-start: 0;
  width: 9px;
  height: 1px;
  background-color: oklch(var(--c-gold) / 0.55);
}
.measure__witness--head {
  top: 0;
}
.measure__witness--foot {
  bottom: 0;
}

/* The station. Reads the same `--run` as the rail, so the two can never
   disagree about where you are. */
.measure__station {
  position: absolute;
  inset-inline-start: 0;
  top: 0;
  width: 9px;
  height: 2.5px;
  background-color: oklch(var(--c-gold));
  transform: translateY(calc(var(--run, 0) * var(--span, 0px)));
}
</style>
