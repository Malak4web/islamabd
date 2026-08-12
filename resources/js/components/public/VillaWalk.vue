<template>
  <section
    ref="root"
    class="walk"
    :class="{ 'walk--live': live }"
    :style="{ '--rooms': rooms.length }"
    :aria-label="$t('walk.region_label')"
  >
    <div class="walk__stage">
      <!-- First in the DOM so that in the essay layout it is the section's
           heading. In the live layout it is absolutely positioned and its
           z-index, not its order, decides where it sits. -->
      <div class="walk__hud">
        <h2 class="walk__heading">{{ $t('walk.region_label') }}</h2>

        <div class="walk__annot">
          <!-- The plan is a drawing, so it does not mirror in Arabic. Only the
               block it sits in follows the reading direction. -->
          <svg
            class="walk__plan"
            viewBox="0 0 100 66"
            role="img"
            :aria-label="$t('walk.plan_label')"
          >
            <g class="plan__rooms">
              <rect
                v-for="(room, i) in rooms"
                :key="`p-${room.key}`"
                :ref="(el) => (planCells[i] = el)"
                :x="room.plan.x"
                :y="room.plan.y"
                :width="room.plan.w"
                :height="room.plan.h"
                :class="['plan__cell', { 'plan__cell--open': room.plan.open }]"
              />
            </g>
            <polyline class="plan__route" :points="routePoints" />
            <g ref="marker" class="plan__marker">
              <circle r="2.6" class="plan__marker-dot" />
              <circle r="5.2" class="plan__marker-ring" />
            </g>
          </svg>

          <div class="walk__text">
            <p class="walk__eyebrow">{{ $t('walk.title') }}</p>
            <p class="walk__count">
              <span class="walk__count-n">{{ String(active + 1).padStart(2, '0') }}</span>
              <span class="walk__count-sep">/</span>
              <span>{{ String(rooms.length).padStart(2, '0') }}</span>
            </p>
            <p class="walk__name">{{ rooms[active].name }}</p>
            <p class="walk__note">{{ rooms[active].note }}</p>
          </div>
        </div>
      </div>

      <figure
        v-for="(room, i) in rooms"
        :key="room.key"
        class="walk__room"
        :style="{ '--i': i, zIndex: i + 1 }"
      >
        <!-- All five lazy, including the first. The whole section is below the
             fold, the hero already claims one eager full-bleed plate, and a
             second competing for it costs the page its LCP for a photograph
             nobody has scrolled to yet. -->
        <img
          class="walk__plate"
          :src="room.src"
          :srcset="room.srcset"
          sizes="100vw"
          :alt="room.alt"
          loading="lazy"
          decoding="async"
          width="1600"
          height="1067"
        />
        <!-- The threshold. A hairline arch that expands past you as you enter —
             the doorway you just walked through, leaving your peripheral
             vision. It is the single cue that turns a zoom into a step
             forward. -->
        <span class="walk__gate" aria-hidden="true"></span>
        <!-- The room falls into shadow behind you. See the note on `--sh`. -->
        <span class="walk__shade" aria-hidden="true"></span>
        <!-- A real caption, visible in the essay layout and taken out of the
             flow only once the HUD is live and saying the same thing. The
             inverse — an `sr-only` caption promoted on a condition — hides the
             text in the one state where nothing else is showing it. -->
        <figcaption class="walk__caption">
          <span class="walk__caption-name">{{ room.name }}</span>
          <span class="walk__caption-note">{{ room.note }}</span>
        </figcaption>
      </figure>

      <span class="walk__veil" aria-hidden="true"></span>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { addFrameReader, clamp, prefersReducedMotion } from '@/lib/motion'
import { srcset, fallback } from '@/lib/media'

/**
 * A walk through a finished villa.
 *
 * The brief every studio site fails: make scrolling feel like moving through a
 * space rather than down a list. The usual answer is a WebGL model, which on
 * this site would be the wrong answer twice over — several hundred kilobytes of
 * renderer, and a procedural room that looks worse than the photographs it is
 * standing in front of. This practice's product IS the photography. So the
 * camera moves through the photographs.
 *
 * The forward motion is sold by two things happening at once, which is exactly
 * how the eye reads walking through a door:
 *
 *   · what you are passing EXPANDS and leaves the frame at its edges
 *   · what is ahead GROWS from smaller, in the middle
 *
 * A crossfade alone reads as a slideshow. The outgoing plate continuing to
 * scale past the frame while the incoming one arrives from 0.86 is what makes
 * it a step rather than a cut — and the arched gate, a hairline that expands
 * past your periphery on every threshold, is what names the movement.
 *
 * Every transform here is `scale` and `opacity`. Nothing repaints; five
 * full-viewport plates ride the compositor.
 */

const { t } = useI18n()

const root = ref(null)
const marker = ref(null)
const planCells = ref([])
/** Off until JS has confirmed it can drive the camera. See the note below. */
const live = ref(false)
const active = ref(0)

/* --------------------------------------------------------------------------
   The house

   Five spaces in the order you would actually be shown them: you arrive along
   the wall, come into the hall, through to the majlis, into the family room,
   and out to the terrace. The plan coordinates describe the same circulation,
   so the drawing in the corner is the route you are walking and not a
   decoration that happens to have five boxes in it.
   -------------------------------------------------------------------------- */
const PLAN = [
  { x: 4, y: 25, w: 12, h: 17 },
  { x: 16, y: 15, w: 22, h: 37 },
  { x: 38, y: 11, w: 28, h: 41 },
  { x: 66, y: 11, w: 22, h: 22 },
  { x: 66, y: 33, w: 30, h: 22, open: true },
]

const SPACES = [
  { key: 'approach', base: '/images/projects/exterior-1' },
  { key: 'hall', base: '/images/projects/hospitality-2' },
  { key: 'majlis', base: '/images/projects/residential-2' },
  { key: 'family', base: '/images/projects/residential-1' },
  { key: 'terrace', base: '/images/projects/landscape-1' },
]

const WIDTHS = [800, 1600]

const rooms = computed(() =>
  SPACES.map((space, i) => ({
    key: space.key,
    src: fallback(space.base, WIDTHS),
    srcset: srcset(space.base, WIDTHS),
    name: t(`walk.spaces.${space.key}.name`),
    note: t(`walk.spaces.${space.key}.note`),
    alt: t(`walk.spaces.${space.key}.alt`),
    plan: PLAN[i],
  }))
)

const centroids = PLAN.map((p) => [p.x + p.w / 2, p.y + p.h / 2])
const routePoints = centroids.map(([x, y]) => `${x},${y}`).join(' ')

/* --------------------------------------------------------------------------
   The camera
   -------------------------------------------------------------------------- */

/**
 * `u` is a room's position in the walk: negative is a room ahead of you, 0 is
 * the room you are standing in, 1 is one you have walked out the far side of.
 *
 * ---------------------------------------------------------------------------
 * The handover is a DOOR, not a dissolve.
 *
 * The obvious build is a crossfade, and it is wrong here for a reason specific
 * to scroll: a timed dissolve is over in 200ms and nobody studies the middle of
 * it, but a scroll-driven one can be parked on. Measured across the runway, the
 * mid-point of a crossfade between two full-bleed photographs left the covered
 * plate contributing 27% of the frame — a double exposure a visitor can stop
 * and look at. Darkening the outgoing room helped the ghost and hurt everything
 * else: it dimmed the photograph you were still standing in.
 *
 * So the arriving room is never transparent. It is revealed through an arched
 * opening that starts the size of a door and expands past your periphery — you
 * see INTO the next room through the doorway, then you walk through it. The
 * gold hairline is the edge of that same opening, computed from the same
 * numbers, so the arch you see is literally the frame of the reveal.
 *
 * Two plates are on screen at once and neither is ever a ghost: one fills the
 * frame, the other fills the door. That is what walking through a house looks
 * like.
 * ---------------------------------------------------------------------------
 */

/** How much of a room's runway is spent approaching and passing its door. */
const ENTER = 0.32

/** Rest at full-bleed, then grow as its walls pass. Never below 1 — an arriving
 *  plate smaller than the frame would show its own hard edge inside the door. */
function plateScale(u) {
  return 1 + Math.max(u, 0) * 0.44
}

/**
 * The opening, as a multiple of the door's own size.
 *
 * Eased hard (t^2.4) rather than linearly: a doorway you are walking toward
 * barely changes size until you are almost at it, then leaves your vision
 * almost at once. A linear ramp reads as a shape being scaled, which is exactly
 * what it must not read as.
 */
function apertureK(u, kEnd) {
  const t = clamp((u + ENTER) / ENTER, 0, 1)
  return 1 + (kEnd - 1) * Math.pow(t, 2.4)
}

/** The hairline fades out before the opening does, so the last thing to pass is
 *  the room itself and not a line. */
function gateOpacity(u, first) {
  if (first || u < -ENTER || u > -0.02) return 0
  return Math.sin(((u + ENTER) / ENTER) * Math.PI) * 0.9
}

/**
 * The room falls into shadow behind you — mild, and only once the next door is
 * already open, so the plate you are looking at is never the dim one.
 */
function shade(u, last) {
  if (last || u < 0.72) return 0
  return clamp((u - 0.72) / 0.28, 0, 1) * 0.55
}

let off = null
let resizeObserver = null
let lastIndex = -1

/**
 * The door, in pixels.
 *
 * Measured rather than expressed in percentages, for one reason: the opening is
 * drawn twice — once as a `clip-path` on the arriving plate and once as the
 * gold outline around it — and the two have to land on exactly the same curve.
 * A percentage radius inside `inset()` resolves against a box the two do not
 * share. Absolute lengths from one measurement cannot disagree.
 *
 * `kEnd` is the multiple at which the opening has cleared the frame in both
 * axes, with a margin so the arch's own curve is off-screen too. It depends on
 * the viewport's shape — a phone needs the door four and a half times its own
 * height, a wide desktop five times its width — so it is computed, not chosen.
 */
const door = { w: 0, h: 0, base: 0, kEnd: 4, stageW: 0, stageH: 0 }

function measureDoor() {
  const stage = root.value?.querySelector('.walk__stage')
  if (!stage) return
  const rect = stage.getBoundingClientRect()
  const W = Math.max(1, rect.width)
  const H = Math.max(1, rect.height)
  const w = Math.min(W, H) * 0.34
  const h = w * 1.5
  // The door stands on the floor, not in the middle of the air: its sill sits
  // near the foot of the frame and the opening grows up and outward from there.
  const base = H * 0.93
  door.w = w
  door.h = h
  door.base = base
  door.stageW = W
  door.stageH = H
  door.kEnd = 1.14 * Math.max(W / w, H / h, base / h)

  stage.style.setProperty('--door-w', `${w.toFixed(1)}px`)
  stage.style.setProperty('--door-h', `${h.toFixed(1)}px`)
  stage.style.setProperty('--door-base', `${(H - base).toFixed(1)}px`)
}

/** The `inset()` for a given opening size, and nothing else — see `door`. */
function apertureClip(k) {
  const { w, h, base, stageW: W, stageH: H } = door
  const aw = w * k
  const ah = h * k
  // The sill travels down and out of frame as the opening passes you, so the
  // one straight edge on this shape is gone before it could read as a line
  // crossing the photograph.
  const sill = base + H * (k - 1) * 0.26
  const top = sill - ah
  const left = (W - aw) / 2
  const rx = aw / 2
  const ry = ah * 0.3
  return (
    `inset(${top.toFixed(1)}px ${(W - left - aw).toFixed(1)}px ` +
    `${(H - sill).toFixed(1)}px ${left.toFixed(1)}px round ` +
    `${rx.toFixed(1)}px ${rx.toFixed(1)}px 0 0 / ${ry.toFixed(1)}px ${ry.toFixed(1)}px 0 0)`
  )
}

function read() {
  const el = root.value
  if (!el) return

  const rect = el.getBoundingClientRect()
  const vh = window.innerHeight || document.documentElement.clientHeight || 0
  const travel = rect.height - vh
  const s = travel > 0 ? clamp(-rect.top / travel, 0, 1) : 0
  const n = rooms.value.length

  const figures = el.querySelectorAll('.walk__room')
  for (let i = 0; i < figures.length; i++) {
    const u = s * n - i
    const node = figures[i]
    // Two ways to be invisible: not yet arrived at its door, or fully covered
    // by the room after it. Both are `visibility: hidden` rather than a
    // transparent layer, so the compositor stops carrying four full-viewport
    // plates it cannot show either way.
    const shown = u > -ENTER - 0.02 && (u < 1.02 || i === n - 1)
    node.style.visibility = shown ? 'visible' : 'hidden'
    node.style.setProperty('--sc', plateScale(u).toFixed(4))
    node.style.setProperty('--sh', shade(u, i === n - 1).toFixed(4))
    node.style.setProperty('--g', gateOpacity(u, i === 0).toFixed(4))

    // The clip runs only while this room is being entered. Outside that window
    // it is removed entirely rather than left at a covering value — a live
    // `clip-path` on a full-viewport element is a mask layer the compositor
    // carries for as long as it is set.
    const entering = i > 0 && shown && u < 0 && u >= -ENTER
    if (entering) {
      const k = apertureK(u, door.kEnd)
      node.style.clipPath = apertureClip(k)
      node.style.setProperty('--gs', k.toFixed(4))
      node.style.setProperty(
        '--gy',
        `${(door.stageH * (k - 1) * 0.26).toFixed(1)}px`
      )
    } else if (node.style.clipPath) {
      node.style.clipPath = ''
    }
  }

  // A room is perceptually the room you are in for `u ∈ [-0.2, 0.8]` — it has
  // already taken the frame before its own `u` reaches 0, because the one
  // behind it is busy leaving. Both the marker and the label are offset to that
  // window rather than to `u = 0`, or the plan and the name lag a third of a
  // screen behind the photograph they are annotating.
  const LEAD = 0.28

  // The marker walks the route continuously rather than hopping between rooms:
  // a plan that only updates on arrival is a legend, not a position.
  const pos = clamp(s * n + LEAD - 0.5, 0, n - 1)
  const i = Math.min(n - 2, Math.floor(pos))
  const f = pos - i
  const [ax, ay] = centroids[i]
  const [bx, by] = centroids[i + 1]
  if (marker.value) {
    marker.value.setAttribute(
      'transform',
      `translate(${(ax + (bx - ax) * f).toFixed(2)} ${(ay + (by - ay) * f).toFixed(2)})`
    )
  }

  const index = clamp(Math.floor(s * n + LEAD), 0, n - 1)
  if (index !== lastIndex) {
    lastIndex = index
    active.value = index
    planCells.value.forEach((cell, j) => {
      if (cell) cell.toggleAttribute('data-on', j === index)
    })
  }
}

onMounted(() => {
  // The default state of this section is a plain stacked photo essay: five
  // figures in flow, every caption legible, nothing hidden. `walk--live` — the
  // only class that pins, stacks and hides anything — is added here, after the
  // code that can also un-hide them is known to run. Reduced motion never gets
  // it, and neither does a headless render.
  if (prefersReducedMotion() || typeof requestAnimationFrame !== 'function') return

  live.value = true
  planCells.value.forEach((cell, j) => cell?.toggleAttribute('data-on', j === 0))

  // After the class has landed. `read()` divides by the section's own height,
  // and until `walk--live` applies that height is the photo essay's, not the
  // runway's — one frame of a wildly wrong camera.
  nextTick(() => {
    measureDoor()
    read()
    off = addFrameReader(read)

    if (typeof ResizeObserver === 'function') {
      resizeObserver = new ResizeObserver(() => {
        measureDoor()
        read()
      })
      resizeObserver.observe(root.value.querySelector('.walk__stage'))
    }
  })
})

onUnmounted(() => {
  off?.()
  off = null
  resizeObserver?.disconnect()
})
</script>

<style scoped>
/* ---------------------------------------------------------------------------
   Default: a photo essay.

   No sticky, no stacking, no camera. This is what renders if JS never runs, if
   the visitor asked for less motion, or if a crawler is reading the page. Every
   plate is in flow at its own size and every caption is present.
   --------------------------------------------------------------------------- */
.walk {
  position: relative;
  background-color: oklch(var(--c-ink));
}

.walk__stage {
  display: grid;
  gap: 1px;
}

.walk__room {
  position: relative;
  margin: 0;
  overflow: hidden;
}

.walk__plate {
  display: block;
  width: 100%;
  height: 60svh;
  min-height: 18rem;
  object-fit: cover;
}

.walk__gate,
.walk__shade,
.walk__veil {
  display: none;
}

.walk__hud {
  padding: 3.5rem 1.5rem 1.5rem;
  color: oklch(var(--c-canvas));
}
@media (min-width: 640px) {
  .walk__hud {
    padding: 4.5rem 2rem 2rem;
  }
}

/* In the essay the running annotation is redundant — every plate carries its
   own caption — so only the section's heading survives. */
.walk:not(.walk--live) .walk__annot {
  display: none;
}

.walk__heading {
  font-family: var(--font-display);
  font-size: clamp(1.65rem, 1.3rem + 1.5vw, 2.45rem);
  font-weight: 300;
  line-height: 1.15;
  max-width: 22ch;
  color: oklch(var(--c-canvas));
}

.walk__caption {
  position: absolute;
  inset-inline: 0;
  bottom: 0;
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  padding: 4rem 1.5rem 1.5rem;
  color: oklch(var(--c-canvas));
  background-image: linear-gradient(
    to top,
    oklch(var(--c-ink) / 0.9) 0%,
    oklch(var(--c-ink) / 0.55) 40%,
    oklch(var(--c-ink) / 0) 100%
  );
}
.walk__caption-name {
  font-family: var(--font-display);
  font-size: 1.6rem;
  font-weight: 300;
}
.walk__caption-note {
  max-width: 46ch;
  color: oklch(var(--c-canvas) / 0.76);
}

/* ---------------------------------------------------------------------------
   Live: the camera.
   --------------------------------------------------------------------------- */

/* The runway. The stage pins for `height - 100svh` of scrolling, so 0.82 of a
   viewport per room is what each space is worth — long enough to read the
   annotation, short enough that five of them are four screens and not eight. */
.walk--live {
  height: calc((var(--rooms, 5) * 0.82 + 1) * 100svh);
}

.walk--live .walk__stage {
  position: sticky;
  top: 0;
  display: block;
  height: 100svh;
  /* Load-bearing: the plates scale past 1.4 and this is the only thing between
     that and a horizontal scrollbar on the document. */
  overflow: hidden;
  gap: 0;
}

/* Live: the HUD says all of this, once, for whichever room you are in. */
.walk--live .walk__heading {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip-path: inset(50%);
  white-space: nowrap;
}
.walk--live .walk__caption {
  position: absolute;
  width: 1px;
  height: 1px;
  inset: auto;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip-path: inset(50%);
  white-space: nowrap;
  background: none;
}

/* No opacity here at all. A plate is either fully painted or not painted — the
   only thing that ever changes is the shape of the opening it is seen
   through. */
.walk--live .walk__room {
  position: absolute;
  inset: 0;
  overflow: hidden;
  visibility: hidden;
}

/* `scale` rather than `transform` so the two never contend, and so a hover or
   press rule landing here later cannot flatten the camera. */
.walk--live .walk__plate {
  height: 100%;
  min-height: 0;
  scale: var(--sc, 1);
}

/* The gold edge of the opening. Its box is the door measured in `measureDoor`
   and its `border-radius` is the same 50%/30% the clip's radii are computed
   from — so at every `k` the hairline sits exactly on the boundary of the
   reveal rather than near it. Scaling from the sill (`transform-origin`) is
   what keeps the door standing on the floor as it grows. */
.walk--live .walk__gate {
  display: block;
  position: absolute;
  z-index: 3;
  left: 50%;
  bottom: var(--door-base, 7%);
  width: var(--door-w, 30%);
  height: var(--door-h, 45%);
  margin-inline-start: calc(var(--door-w, 30%) / -2);
  border: 1px solid oklch(var(--c-gold) / 0.9);
  /* No sill. A doorway's jambs and head sweep past you; its threshold passes
     under your feet, and drawn in it reads as a stray horizontal line crossing
     the photograph at exactly the wrong moment. */
  border-bottom-width: 0;
  border-radius: 50% 50% 0 0 / 30% 30% 0 0;
  opacity: var(--g, 0);
  transform: translateY(var(--gy, 0px)) scale(var(--gs, 1));
  transform-origin: 50% 100%;
  pointer-events: none;
}

.walk--live .walk__shade {
  display: block;
  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;
  background-color: oklch(var(--c-ink));
  opacity: var(--sh, 0);
}

.walk--live .walk__veil {
  display: block;
  position: absolute;
  inset: 0;
  z-index: 6;
  pointer-events: none;
  /* The annotation sits on photographs this component does not choose, so its
     ground is guaranteed rather than hoped for: >=12:1 for the name at the
     foot, clearing to nothing by the middle so the room is still the picture. */
  background-image: linear-gradient(
    to top,
    oklch(var(--c-ink) / 0.92) 0%,
    oklch(var(--c-ink) / 0.74) 16%,
    oklch(var(--c-ink) / 0.4) 34%,
    oklch(var(--c-ink) / 0) 58%
  );
}

.walk--live .walk__hud {
  position: absolute;
  z-index: 7;
  inset-inline-start: 0;
  bottom: 0;
  width: 100%;
  /* The stage is a full `svh`, and on a phone the bottom of that is under the
     tab bar. `--shell-bottom` is the one place that height is stated, and it is
     0 from `lg` where the bar does not exist. */
  padding: 0 1.5rem calc(var(--shell-bottom) + 1.25rem);
}

@media (min-width: 640px) {
  .walk--live .walk__hud {
    padding: 0 2rem calc(var(--shell-bottom) + 2.5rem);
  }
}

.walk__eyebrow {
  font-family: var(--font-body);
  font-size: 0.8125rem;
  font-weight: 700;
  color: oklch(var(--c-gold));
}

/* Stacked on a phone. Side by side, the plan takes a third of a 390px screen
   and leaves the room's own name breaking across four lines beside it. */
.walk__annot {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.9rem;
}

.walk__plan {
  flex: none;
  width: 7rem;
  height: auto;
  overflow: visible;
}
@media (min-width: 640px) {
  .walk__annot {
    flex-direction: row;
    align-items: flex-end;
    gap: 2.25rem;
  }
  .walk__plan {
    width: 11.5rem;
  }
}

.plan__cell {
  fill: oklch(var(--c-canvas) / 0.06);
  stroke: oklch(var(--c-gold) / 0.55);
  stroke-width: 0.9;
  transition: fill var(--dur-base) var(--ease-out-quart),
    stroke var(--dur-base) var(--ease-out-quart);
}
/* The terrace is outside. An open-air space is drawn as one. */
.plan__cell--open {
  stroke-dasharray: 3 2.4;
}
.plan__cell[data-on] {
  fill: oklch(var(--c-gold) / 0.38);
  stroke: oklch(var(--c-gold));
}

.plan__route {
  fill: none;
  stroke: oklch(var(--c-canvas) / 0.4);
  stroke-width: 0.7;
  stroke-dasharray: 2 2;
}

.plan__marker-dot {
  fill: oklch(var(--c-gold));
}
.plan__marker-ring {
  fill: none;
  stroke: oklch(var(--c-gold) / 0.75);
  stroke-width: 0.9;
}

.walk__text {
  min-width: 0;
}

.walk__count {
  margin-top: 0.55rem;
  font-family: var(--font-body);
  font-size: 0.8125rem;
  font-variant-numeric: tabular-nums;
  color: oklch(var(--c-canvas) / 0.6);
}
.walk__count-n {
  font-weight: 700;
  color: oklch(var(--c-canvas));
}
.walk__count-sep {
  margin: 0 0.4rem;
}

.walk__name {
  margin-top: 0.35rem;
  font-family: var(--font-display);
  font-size: clamp(1.65rem, 1.3rem + 1.5vw, 2.45rem);
  font-weight: 300;
  line-height: 1.15;
  color: oklch(var(--c-canvas));
}

.walk__note {
  margin-top: 0.5rem;
  max-width: 34ch;
  color: oklch(var(--c-canvas) / 0.72);
}

/* The annotation is the one thing here that is allowed a transition: the plates
   are driven per frame and must not be smoothed, but the name changes five
   times in four screens and a hard cut on it reads as a glitch. */
.walk__name,
.walk__note,
.walk__count-n {
  transition: opacity var(--dur-fast) var(--ease-out-quart);
}
</style>
