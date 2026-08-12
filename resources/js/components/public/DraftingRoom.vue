<template>
  <!-- Decorative: the room says the same thing the copy beside it says, in the
       practice's own language. It is aria-hidden and carries no information a
       visitor could miss. -->
  <canvas ref="canvas" class="block h-full w-full" aria-hidden="true"></canvas>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { addFrameReader, prefersReducedMotion, viewportProgress } from '@/lib/motion'

/**
 * An axonometric room, drawn.
 *
 * The third dimension on this site is space, not objects. An interior
 * architect's own 3D is the axonometric — the drawing convention that shows a
 * room's three dimensions at once with no perspective distortion, because a
 * drawing has to be measurable. So this is not a render of a room; it is the
 * drawing of one, laid down stroke by stroke the way a plotter lays down a
 * sheet, in the same gold hairline the rest of the site rules its lines with.
 *
 * Hand-built rather than pulled from a 3D library. The whole model is a few
 * dozen edges and one rotation matrix, and the alternative is shipping several
 * hundred kilobytes of renderer to draw lines — on a page whose hero photograph
 * is the thing that should own the network budget. It also means the output is
 * a drawing rather than a render, which is the entire point.
 */

const props = defineProps({
  /**
   * Resting azimuth, degrees. The three numbers below are one decision, not
   * three: the azimuth can reach `angle ± (sway + scrollTurn)`, and an
   * axonometric only reads between roughly 20 and 60 degrees — outside that
   * band the room turns face-on and collapses into overlapping rectangles.
   * 40 ± 18 keeps the whole range inside it, with 45 (the military axonometric)
   * passed through rather than parked on.
   */
  angle: { type: Number, default: 40 },
  /** Half-width of the sway, degrees. The room breathes; it does not spin. */
  sway: { type: Number, default: 6 },
  /** Seconds for one full sway cycle. */
  period: { type: Number, default: 19 },
  /**
   * Degrees the azimuth swings across the band's own travel through the
   * viewport. This is what makes the scroll spatial rather than vertical: you
   * are not scrolling past a picture of a room, you are walking around it.
   */
  scrollTurn: { type: Number, default: 12 },
  /** Seconds between section cuts. */
  cutPeriod: { type: Number, default: 13 },
  /** Fraction of the inline axis the model is offset toward the trailing edge. */
  offset: { type: Number, default: 0.2 },
  /** Peak stroke alpha for structure. Grid and fill lines sit below it. */
  intensity: { type: Number, default: 0.78 },
  /** Share of the shortest axis the drawing occupies. It is a drawing ON a
      sheet: it needs the margin to read as one. */
  fit: { type: Number, default: 0.8 },
})

const canvas = ref(null)

/* --------------------------------------------------------------------------
   The model

   Units are metres, origin on the floor at the room's centre. Nothing here is
   arbitrary: it is a 2.6 x 2.1 room at 1.5 ceiling with a door, a window, a
   dropped soffit and two pieces of massing, which is the smallest set of
   elements that still reads as an interior rather than as a cube.
   -------------------------------------------------------------------------- */

const W = 2.6
const D = 2.1
const H = 1.5

const HALF_W = W / 2
const HALF_D = D / 2

/**
 * The fit-out, held as volumes rather than as loose edges — the section sweep
 * has to be able to ask what it is cutting through, and a bag of line segments
 * cannot answer that.
 */
const MASSING = [
  { x0: -0.95, x1: 0.3, y0: 0, y1: 0.42, z0: -0.8, z1: -0.28 },
  { x0: 0.5, x1: 1.12, y0: 0, y1: 0.44, z0: 0.12, z1: 0.74 },
]

/** 12 edges of a rectangular volume. */
function box(x0, x1, y0, y1, z0, z1) {
  const c = [
    [x0, y0, z0], [x1, y0, z0], [x1, y0, z1], [x0, y0, z1],
    [x0, y1, z0], [x1, y1, z0], [x1, y1, z1], [x0, y1, z1],
  ]
  return [
    [c[0], c[1]], [c[1], c[2]], [c[2], c[3]], [c[3], c[0]],
    [c[4], c[5]], [c[5], c[6]], [c[6], c[7]], [c[7], c[4]],
    [c[0], c[4]], [c[1], c[5]], [c[2], c[6]], [c[3], c[7]],
  ]
}

/** A closed rectangle lying in one plane, given four corners. */
function loop(...points) {
  return points.map((point, i) => [point, points[(i + 1) % points.length]])
}

function buildEdges() {
  const grid = []
  // The setting-out grid, struck first — the sheet is ruled before anything is
  // drawn on it. 350mm module, which is the same module the CTA's flat grid
  // uses, so the two read as one drawing seen twice.
  for (let x = -HALF_W + 0.35; x < HALF_W - 0.01; x += 0.35) {
    grid.push([[x, 0, -HALF_D], [x, 0, HALF_D]])
  }
  for (let z = -HALF_D + 0.35; z < HALF_D - 0.01; z += 0.35) {
    grid.push([[-HALF_W, 0, z], [HALF_W, 0, z]])
  }

  const floor = loop(
    [-HALF_W, 0, -HALF_D], [HALF_W, 0, -HALF_D], [HALF_W, 0, HALF_D], [-HALF_W, 0, HALF_D]
  )

  // Two walls only. A closed box hides its own interior; an architect draws the
  // two that let you see in.
  const walls = [
    [[-HALF_W, 0, -HALF_D], [-HALF_W, H, -HALF_D]],
    [[HALF_W, 0, -HALF_D], [HALF_W, H, -HALF_D]],
    [[-HALF_W, 0, HALF_D], [-HALF_W, H, HALF_D]],
    [[-HALF_W, H, -HALF_D], [HALF_W, H, -HALF_D]],
    [[-HALF_W, H, -HALF_D], [-HALF_W, H, HALF_D]],
  ]

  // A door in the back wall and a window in the side one. Openings are what
  // make a wall a wall rather than a plane.
  const door = [
    [[0.3, 0, -HALF_D], [0.3, 1.12, -HALF_D]],
    [[1.0, 0, -HALF_D], [1.0, 1.12, -HALF_D]],
    [[0.3, 1.12, -HALF_D], [1.0, 1.12, -HALF_D]],
  ]
  const window = [
    ...loop(
      [-HALF_W, 0.58, -0.4], [-HALF_W, 0.58, 0.62], [-HALF_W, 1.24, 0.62], [-HALF_W, 1.24, -0.4]
    ),
    [[-HALF_W, 0.58, 0.11], [-HALF_W, 1.24, 0.11]],
  ]

  // A dropped soffit — the ceiling is present without the box being closed.
  const soffit = loop(
    [-HALF_W + 0.3, H - 0.12, -HALF_D + 0.3], [HALF_W - 0.3, H - 0.12, -HALF_D + 0.3],
    [HALF_W - 0.3, H - 0.12, HALF_D - 0.3], [-HALF_W + 0.3, H - 0.12, HALF_D - 0.3]
  )

  const massing = [
    ...MASSING.flatMap((m) => box(m.x0, m.x1, m.y0, m.y1, m.z0, m.z1)),
    // The rug, a hair above the floor so it reads as laid on it, not cut into it.
    ...loop(
      [-1.05, 0.004, -0.9], [0.55, 0.004, -0.9], [0.55, 0.004, 0.45], [-1.05, 0.004, 0.45]
    ),
  ]

  // A dimension line off the front edge, with its witness ticks. This is the
  // one element that is not part of the room: it is the drawing admitting it is
  // a drawing, and it is why the whole thing reads as engineering.
  const dim = [
    [[-HALF_W, 0, HALF_D + 0.26], [HALF_W, 0, HALF_D + 0.26]],
    [[-HALF_W, 0, HALF_D + 0.14], [-HALF_W, 0, HALF_D + 0.38]],
    [[HALF_W, 0, HALF_D + 0.14], [HALF_W, 0, HALF_D + 0.38]],
    [[-HALF_W, 0, HALF_D + 0.04], [-HALF_W, 0, HALF_D + 0.3]],
    [[HALF_W, 0, HALF_D + 0.04], [HALF_W, 0, HALF_D + 0.3]],
  ]

  // Construction order. The draw-on follows this list, so the room assembles
  // the way it would be drawn: sheet, floor, structure, openings, fit-out,
  // dimensions.
  return [
    ...grid.map((e) => ({ e, w: 'faint' })),
    ...floor.map((e) => ({ e, w: 'structure' })),
    ...walls.map((e) => ({ e, w: 'structure' })),
    ...door.map((e) => ({ e, w: 'structure' })),
    ...window.map((e) => ({ e, w: 'detail' })),
    ...soffit.map((e) => ({ e, w: 'faint' })),
    ...massing.map((e) => ({ e, w: 'detail' })),
    ...dim.map((e) => ({ e, w: 'detail' })),
  ].map((edge) => {
    const [a, b] = edge.e
    const len = Math.hypot(b[0] - a[0], b[1] - a[1], b[2] - a[2])
    return { ...edge, len }
  })
}

const EDGES = buildEdges()
const TOTAL_LENGTH = EDGES.reduce((sum, edge) => sum + edge.len, 0)
const WEIGHT = { faint: 0.3, detail: 0.68, structure: 1 }

/** 26 degrees of elevation: high enough to read the plan, low enough to read the section. */
const ELEVATION = (26 * Math.PI) / 180
const COS_E = Math.cos(ELEVATION)
const SIN_E = Math.sin(ELEVATION)

const DRAW_MS = 2600

/* --------------------------------------------------------------------------
   Renderer
   -------------------------------------------------------------------------- */

let ctx = null
let raf = 0
let observer = null
let resizeObserver = null
let started = 0
let visible = false
let stroke = (alpha) => `rgba(197, 168, 128, ${alpha})`
let view = { w: 0, h: 0, scale: 1, cx: 0, cy: 0, near: 1, far: -1 }
/** Degrees of azimuth contributed by the band's position in the viewport. */
let scrollTurn = 0
let offScroll = null
/** False under reduced motion: no sway, no scroll coupling, no section. */
let ambient = true

function project(point, cosA, sinA) {
  const x = point[0] * cosA + point[2] * sinA
  const z = -point[0] * sinA + point[2] * cosA
  return [x, point[1] * COS_E - z * SIN_E, point[1] * SIN_E + z * COS_E]
}

/**
 * Resolve the brand gold out of the cascade rather than restating it here.
 * Canvas takes an `oklch()` string in current engines; where it does not, the
 * assignment silently fails to apply and `strokeStyle` keeps its old value —
 * which is exactly the probe used below to fall back to the sRGB equivalent.
 */
function makeStroke(el, context) {
  const triple = getComputedStyle(el).getPropertyValue('--c-gold').trim()
  if (triple) {
    context.strokeStyle = '#000000'
    context.strokeStyle = `oklch(${triple} / 0.5)`
    if (context.strokeStyle !== '#000000') {
      return (alpha) => `oklch(${triple} / ${alpha})`
    }
  }
  return (alpha) => `rgba(197, 168, 128, ${alpha})`
}

/**
 * Fits the model to the box across the WHOLE azimuth range it can reach — the
 * sway and the scroll swing together — so the drawing never rescales while it
 * is turning. Nine samples rather than the three extremes: an axonometric's
 * silhouette is widest somewhere in the middle of a swing, not at its ends.
 */
function measure(el) {
  const rect = el.getBoundingClientRect()
  const w = Math.max(1, Math.round(rect.width))
  const h = Math.max(1, Math.round(rect.height))
  const dpr = Math.min(2, window.devicePixelRatio || 1)

  el.width = Math.round(w * dpr)
  el.height = Math.round(h * dpr)
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0)

  let minX = Infinity
  let maxX = -Infinity
  let minY = Infinity
  let maxY = -Infinity
  let near = -Infinity
  let far = Infinity

  const swing = props.sway + props.scrollTurn
  for (let i = 0; i <= 8; i++) {
    const offset = -swing + (swing * 2 * i) / 8
    const a = ((props.angle + offset) * Math.PI) / 180
    const cosA = Math.cos(a)
    const sinA = Math.sin(a)
    for (const { e } of EDGES) {
      for (const point of e) {
        const [x, y, z] = project(point, cosA, sinA)
        if (x < minX) minX = x
        if (x > maxX) maxX = x
        if (y < minY) minY = y
        if (y > maxY) maxY = y
        if (z > near) near = z
        if (z < far) far = z
      }
    }
  }

  // The margin is what makes it read as a drawing laid on a sheet rather than a
  // diagram bled to the edge. Without it the dimension line runs off, and that
  // is the one element that must not.
  const scale = Math.min((w * props.fit) / (maxX - minX), (h * props.fit) / (maxY - minY))
  const dirSign = Number(getComputedStyle(el).getPropertyValue('--dir-sign')) || 1

  // The offset hands the reading side back to the type, but it can never cost
  // the drawing an edge. On a wide banner there is room to spare and the full
  // shift applies; on a phone, where the fit is width-bound, the slack is a few
  // dozen pixels and the shift collapses to it. Without the clamp the phone
  // simply loses the left third of the room off the canvas.
  // Three quarters of the slack, not all of it: spending the last quarter puts
  // the drawing flush against the canvas edge, which reads as clipped even when
  // every line is inside.
  const slack = Math.max(0, (w - (maxX - minX) * scale) / 2) * 0.75
  const shift = Math.min(w * props.offset, slack) * dirSign

  view = {
    w,
    h,
    scale,
    cx: w / 2 + shift - ((minX + maxX) / 2) * scale,
    cy: h / 2 + ((minY + maxY) / 2) * scale,
    near,
    far,
  }
}

/**
 * The section cut.
 *
 * A vertical plane travelling along the room's long axis, drawn where it meets
 * the volume — the room's full height and depth at that station, plus the
 * profile of anything it passes through. This is what an interior designer
 * actually does to a space to understand it, so it is the one ambient loop on
 * this page that is doing real work rather than decorating.
 *
 * It travels for the first 42% of the cycle and is absent for the rest: a cut
 * that passes every thirteen seconds is a detail, one that never leaves is a
 * metronome.
 */
function sectionCut(now, cosA, sinA, project2d) {
  const cycle = ((now - started) / 1000) % props.cutPeriod
  const t = cycle / (props.cutPeriod * 0.42)
  if (t >= 1) return

  // Ease across, and fade at both ends so it enters and leaves the wall rather
  // than appearing on it.
  const eased = t * t * (3 - 2 * t)
  const xc = -HALF_W + eased * W
  const fade = Math.sin(t * Math.PI)
  const alpha = props.intensity * fade * 0.95

  const planes = [[0, H, -HALF_D, HALF_D]]
  for (const m of MASSING) {
    if (xc > m.x0 && xc < m.x1) planes.push([m.y0, m.y1, m.z0, m.z1])
  }

  ctx.lineWidth = 1.3
  ctx.strokeStyle = stroke(Number(alpha.toFixed(3)))
  for (const [y0, y1, z0, z1] of planes) {
    const corners = [
      [xc, y0, z0], [xc, y1, z0], [xc, y1, z1], [xc, y0, z1],
    ].map((p) => project2d(project(p, cosA, sinA)))
    ctx.beginPath()
    ctx.moveTo(corners[0][0], corners[0][1])
    for (let i = 1; i < corners.length; i++) ctx.lineTo(corners[i][0], corners[i][1])
    ctx.closePath()
    ctx.stroke()
  }
}

function paint(now, ambient = true) {
  const { w, h, scale, cx, cy, near, far } = view
  ctx.clearRect(0, 0, w, h)

  const elapsed = now - started
  const linear = Math.min(1, Math.max(0, elapsed / DRAW_MS))
  // Expo-out: the plotter arrives at speed and settles, which is how the rest
  // of this site's motion is eased.
  const progress = 1 - Math.pow(2, -10 * linear)
  const drawn = TOTAL_LENGTH * (linear >= 1 ? 1 : progress)

  // Two rotations on one axis. The sway is the room breathing on its own; the
  // scroll term is the visitor walking around it. Adding them rather than
  // choosing between them is what keeps the drawing alive on a screen nobody is
  // touching, and spatial on one somebody is reading.
  const phase = (elapsed / 1000 / props.period) * Math.PI * 2
  const azimuth = ambient
    ? props.angle + Math.sin(phase) * props.sway + scrollTurn
    : props.angle
  const a = (azimuth * Math.PI) / 180
  const cosA = Math.cos(a)
  const sinA = Math.sin(a)

  const span = near - far || 1
  const project2d = (p) => [cx + p[0] * scale, cy - p[1] * scale]

  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'

  let acc = 0
  for (const { e, w: weight, len } of EDGES) {
    if (acc >= drawn) break
    const fraction = Math.min(1, (drawn - acc) / (len || 1))
    acc += len

    const from = project(e[0], cosA, sinA)
    const to = project(e[1], cosA, sinA)

    // Depth reads as atmosphere: an edge at the back of the room is drawn
    // fainter than the one at the front, which is what stops a wireframe from
    // collapsing into a flat tangle of lines.
    const nearness = ((from[2] + to[2]) / 2 - far) / span
    const alpha = props.intensity * WEIGHT[weight] * (0.4 + nearness * 0.6)

    const [x0, y0] = project2d(from)
    const [x1, y1] = project2d(to)

    ctx.strokeStyle = stroke(Number(alpha.toFixed(3)))
    ctx.lineWidth = weight === 'structure' ? 1.15 : 1
    ctx.beginPath()
    ctx.moveTo(x0, y0)
    ctx.lineTo(x0 + (x1 - x0) * fraction, y0 + (y1 - y0) * fraction)
    ctx.stroke()
  }

  // Only once the room exists. A section through a half-drawn room is noise.
  if (ambient && linear >= 1) sectionCut(now, cosA, sinA, project2d)
}

function frame(now) {
  paint(now)
  raf = requestAnimationFrame(frame)
}

/**
 * The scroll term. It rides the shared ticker rather than the paint loop so it
 * costs one rect read per scrolled frame and nothing at all while the page is
 * still — the sway keeps painting either way.
 */
function readScroll() {
  const el = canvas.value
  if (!el) return
  const vh = window.innerHeight || document.documentElement.clientHeight || 0
  scrollTurn = viewportProgress(el.getBoundingClientRect(), vh) * props.scrollTurn
}

function play() {
  if (raf || !visible || typeof document === 'undefined' || document.hidden) return
  if (!started) started = performance.now()
  if (!offScroll) {
    readScroll()
    offScroll = addFrameReader(readScroll)
  }
  raf = requestAnimationFrame(frame)
}

function pause() {
  if (offScroll) {
    offScroll()
    offScroll = null
  }
  if (!raf) return
  cancelAnimationFrame(raf)
  raf = 0
}

const onVisibility = () => (document.hidden ? pause() : play())

onMounted(() => {
  const el = canvas.value
  if (!el || typeof el.getContext !== 'function') return
  ctx = el.getContext('2d')
  if (!ctx) return

  stroke = makeStroke(el, ctx)
  measure(el)

  // Reduced motion gets the finished drawing, not a slower one. It is still the
  // room — it simply arrives already drawn, at its resting azimuth, with no
  // sway, no scroll coupling and no section passing through it.
  ambient = !prefersReducedMotion() && typeof IntersectionObserver === 'function'

  // Set up before the early return, so a rotated phone still gets a sharp
  // drawing rather than the first raster stretched to the new box.
  if (typeof ResizeObserver === 'function') {
    resizeObserver = new ResizeObserver(() => {
      measure(el)
      // `measure` resizes the backing store, which clears it — so a repaint is
      // mandatory, not optional, whenever this fires and no loop is running.
      // Guarded on `started`: before the first play there is no clock to paint
      // against, and painting anyway would draw the finished room and then have
      // the draw-on restart it from nothing.
      if (!raf && started) paint(performance.now(), ambient)
    })
    resizeObserver.observe(el)
  }

  if (!ambient) {
    started = performance.now() - DRAW_MS
    paint(performance.now(), false)
    return
  }

  observer = new IntersectionObserver(
    (entries) => {
      visible = entries.some((entry) => entry.isIntersecting)
      visible ? play() : pause()
    },
    { rootMargin: '10% 0px' }
  )
  observer.observe(el)

  document.addEventListener('visibilitychange', onVisibility)
})

onUnmounted(() => {
  pause()
  observer?.disconnect()
  resizeObserver?.disconnect()
  if (typeof document !== 'undefined') {
    document.removeEventListener('visibilitychange', onVisibility)
  }
})
</script>
