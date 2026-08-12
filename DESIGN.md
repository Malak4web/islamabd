# Design

The visual system for the Eslam Abdulghani Interiors public site. Strategy —
who this is for and why — lives in [PRODUCT.md](PRODUCT.md).

Source of truth for every value here is `resources/css/app.css` (tokens) and
`tailwind.config.js` (the utilities that expose them). If a value below and the
code disagree, the code is right and this file is stale.

---

## Theme

Light, single theme. No dark mode.

The scene that decides it: a prospective client scrolling on a phone in a
brightly lit office or a car in Kuwait or Cairo daylight, deciding whether this
practice is worth a call. Interiors photography is high-key and warm; a dark
chrome would fight every photograph on the page. Light is the only answer that
serves the work.

## Colour

**Strategy: restrained.** Tinted neutrals carry the surface; gold is a material
detail, never a wash. The photographs supply the colour.

Authored in OKLCH. Every neutral sits on **hue 75.4** — the measured hue of the
brand gold — so the greys lean toward the brand's own colour rather than toward
a generic "warm", which is what produced the previous cream cast.

| Token | Value | Hex | Role |
|---|---|---|---|
| `--c-canvas` | `oklch(97.7% 0.0034 75.4)` | `#f9f7f5` | Page ground |
| `--c-surface` | `oklch(95.3% 0.0058 75.4)` | `#f1efeb` | Alternating sections, footer |
| `--c-canvas-inset` | `oklch(93.0% 0.0080 75.4)` | `#ebe7e2` | Image wells, skeletons |
| `--c-ink` | `oklch(17.9% 0.0052 75.4)` | `#13110f` | Headings, primary text |
| `--c-ink-muted` | `oklch(40.0% 0.0098 75.4)` | `#4b4742` | Body copy |
| `--c-ink-subtle` | `oklch(50.5% 0.0106 75.4)` | `#68645e` | Meta, captions, placeholders |
| `--c-line` | `oklch(89.0% 0.0121 75.4)` | `#dfdad2` | Hairlines |
| `--c-line-strong` | `oklch(80.0% 0.0184 75.4)` | `#c5bcb1` | Input borders |
| `--c-gold` | `oklch(74.7% 0.0638 75.4)` | `#C5A880` | **Surface only** — fills, edges, rules |
| `--c-gold-deep` | `oklch(53.6% 0.0751 75.4)` | `#876739` | **Text** — labels, links, small marks |
| `--c-gold-soft` | `oklch(88.0% 0.0345 75.4)` | `#e5d5bf` | Hover beds |
| `--c-gold-wash` | `oklch(95.5% 0.0142 75.4)` | `#f6efe6` | Tinted section ground |

### The one rule that matters

**`--c-gold` cannot carry text.** At 74.7% lightness it measures **2.08:1** on
the canvas — below even the 3:1 large-text floor. It was previously used for
section kickers, "view all" links, footer headings and the label on the primary
CTA; all of those were unreadable. Anything that is *read* uses
`--c-gold-deep` (4.9:1 on canvas, 4.5:1 on surface). Anything that is *seen* —
a rule, a fill, a 1.5px mark — uses `--c-gold`.

Corollary: **never white on gold** (2.26:1). Gold buttons take an ink label
(8.3:1).

### Verified pairings

| Pair | Ratio |
|---|---|
| ink / canvas | 17.6:1 |
| ink-muted / canvas | 8.6:1 |
| ink-subtle / canvas | 5.5:1 |
| gold-deep / canvas | 4.9:1 |
| gold-deep / surface | 4.5:1 |
| ink / gold (button label) | 8.3:1 |
| canvas / ink (inverted CTA band) | 17.6:1 |
| gold / ink (accent on dark) | 8.3:1 |

Re-check with the script in `PRODUCT.md`'s accessibility section before adding
any colour. Do not judge these by eye.

## Typography

**Two voices, in both scripts.**

| Role | Latin | Arabic | Where |
|---|---|---|---|
| Title | `Cormorant Garamond` 300–600 | `Amiri` 400/700 | `h1`–`h6`, via `--font-display` |
| Annotation | `Inter` 300–700 | `Cairo` 300–700 | everything else, via `--font-body` |

Titles are set in a classical book face; everything that *annotates* — labels,
captions, figures, buttons, navigation — stays in the sans. That split is the
drafting convention itself: **the title is hand-set, the dimensions are
machine-lettered.** It is also what keeps a serif from turning into costume,
because the serif only speaks where the practice speaks in its own voice.

Both display faces are high-contrast old-style cuts with sharp, chiselled
serifs — the same family of letterform as the studio's own wordmark. The site
finally looks like its logo. Both are also genuinely paired rather than a Latin
design with an Arabic fallback: the same idea is executed twice, once per
script.

Everything is set through `--font-display` / `--font-body` / `--font-arabic` /
`--font-arabic-display`, so swapping a face is a one-line change in `app.css`
plus the Google Fonts URL in `resources/views/app.blade.php`.

**Arabic needs its own tuning, and it is not optional:**

- `font-size-adjust: 0.5` on Arabic headings. Amiri's own aspect is 0.433, so
  it sets ~15% optically smaller than Cormorant at the same value. **Do not fix
  this with a relative `font-size`** — `font-size: 1.06em` on a heading resolves
  against the *parent*, not the heading, and collapses every Arabic title on
  the site to about seventeen pixels.
- Leading splits by size: `1.32` on `h1`/`h2`, `1.45` on `h3`/`h4`. Amiri has
  deep descenders and long ascenders; Cormorant does not.
- `letter-spacing: 0`, always. Arabic glyphs join and tracking breaks the join.

Fluid modular scale, ratio ≈1.25, all via `clamp()`:

| Class | Range | Face | Use |
|---|---|---|---|
| `text-display` | 3 → 5.9rem | title | Hero headline only |
| `text-title` | 2.2 → 3.85rem | title | Page and section headings |
| `text-heading` | 1.65 → 2.45rem | title | Sub-sections, stat figures |
| `text-subhead` | 1.3 → 1.6rem | title | Card titles |
| `text-lede` | 1.06 → 1.25rem | annotation | Intro paragraphs |
| `text-label` | 0.8125rem | annotation | Meta, captions, buttons |

Rules:
- Display ceiling is **5.9rem**, under the 6rem limit. The steps are ~10% up
  on the values Inter used, because an old-style serif with a small x-height
  sets that much smaller at the same number.
- **Tracking sits near zero at display sizes now.** Inter needed -0.03em to
  stop looking loose; a serif's own serifs close those gaps, and the same value
  pushes Cormorant's hairlines into each other. Only the two smallest steps —
  both set in the sans — carry any tracking at all. Tailwind's
  `tracking-tighter` (-0.05em) collides glyphs at any size: do not use it.
- **Figures that are read as a set get `tabular-nums`** — the hero counter, the
  practice's stats. Cormorant's default figures are old-style, which is why the
  stat row is worth setting large in the title face; `tabular-nums` keeps the
  four of them on a common width.
- Weight, not case, carries emphasis. The hero pairs `font-light` with
  `font-medium` on two lines of the same heading.
- **No `uppercase` on headings.** Arabic has no uppercase, so `uppercase` made
  the English site shout while the Arabic site spoke normally — two different
  designs from one template.
- Body measure capped at `max-w-prose` (68ch); short intros at `max-w-measure`
  (46ch).
- Arabic re-tunes leading (1.85 body, 1.28 headings) and forces
  `letter-spacing: 0` — tracking breaks Arabic glyph joins.

## The app shell

> **On a phone this is an application. On a desktop it is a sheet.**

Below `lg` the site runs inside a fixed shell: a compact bar at the top, a tab
bar at the bottom, and the document scrolling between them. From `lg` the shell
dissolves — the header carries the full navigation and there is no tab bar at
all.

| Token | Value | What it is |
|---|---|---|
| `--bar-h` | `3.875rem` | the top app bar, below `lg` |
| `--tabbar-h` | `3.875rem` | the tab bar |
| `--safe-b` | `env(safe-area-inset-bottom)` | the home indicator; 0 elsewhere |
| `--shell-bottom` | `tabbar + safe-b`, **0 from `lg`** | what every fixed overlay must clear |

Because `--shell-bottom` is 0 above `lg`, the utilities that read it —
`.pb-shell`, `.bottom-shell`, `.h-shell` — are correct on a phone, a tablet in
landscape and a desktop without a breakpoint written at the call site. Use them
instead of a hard-coded `pb-20`; that is how the number goes out of sync.

- **The shell owns the top clearance below `lg`.** `App.vue` pads the router
  outlet by `--bar-h`. A page states only its own rhythm there, and restores
  full clearance at `lg` where the desktop header floats (`PageHeader` is
  `pt-6 sm:pt-8 lg:pt-32`).
- **Five destinations, no drawer.** The whole site map is five routes, which is
  exactly what a tab bar holds — so there is no hamburger, no overlay to
  dismiss and no state to remember. `BottomNav.vue`.
- **A tab stays lit through its section's detail screens.** Neither of
  RouterLink's active classes says that (`active-class` matches on prefix and
  `/` is a prefix of everything; `exact-active-class` goes dark the moment you
  open a project), so `isCurrent()` is written out.
- **The active tab is marked twice**: gold mark and label, plus a 1.5px
  gold rule on the edge facing the content — the same rule the desktop nav
  draws under its current link.
- **The language switcher is in the bar at every width.** It was `hidden
  sm:block`: on a bilingual site the control that switches language was a level
  deep on the devices most visitors use.

## Touch

- **44px in the short axis**, enforced from `app.css` under
  `@media (pointer: coarse)` with `:where()` so specificity stays at 0 and a
  component can still state its own height. Inline anchors inside prose are
  unaffected — an inline box ignores `min-height`.
  Beware: a Tailwind `min-h-[2.5rem]` **beats** that rule. Audit rather than
  assume, with `scratchpad/audit.mjs`.
- **`touch-action: manipulation`** on interactive elements at coarse pointers,
  which drops the browser's 300ms double-tap wait.
- **`-webkit-tap-highlight-color: transparent`, replaced not removed.** Every
  control that loses the grey flash gets `.press` (a 2% transform settle) or
  `.press-sm` (6%). Under reduced motion the press dims instead of moving —
  it is feedback, not decoration, and a tap with no answer reads as broken.
- Never put `.press` on an element that also carries `v-reveal`: the reveal
  animation's `both` fill owns `transform` and the press would never show.

## Rails

`.rail` (always) and `.rail-sm` (below 640px only) are horizontal snap tracks:
filters, the home card sets, and detail-page galleries. A rail is what lets a
phone show a set *as a set* instead of a wrapped block four screens tall, and
the item peeking past the edge is the affordance.

`.rail-bleed` runs the track to the viewport edge while keeping the first item
on the page gutter. Logical properties throughout, so it scrolls right-to-left
in Arabic with no direction code.

Two properties on `.rail` are load-bearing and both are non-obvious:

- **`position: relative`.** `overflow` does not clip an absolutely positioned
  descendant whose containing block sits above the scroller — and cards carry
  several, including Tailwind's `sr-only`. Without this they resolve against an
  ancestor outside the rail, land at the far end of the track, and drag the
  document's layout viewport with them: 390px of content reporting 584px, every
  fixed bar stretched to match.
- **`min-width: 0`.** A grid or flex item's automatic minimum size is its
  content, and a scroll container's max-content size is its whole track — so a
  rail dropped in a grid cell sizes the column to the sum of its items instead
  of scrolling. Grid parents also want `min-w-0` on the cell itself.

`.rail-sm` is written as a `max-width` query, not an `sm:` override: both would
land in the same cascade layer and these rules come after Tailwind's, so a
`sm:grid` would lose to `display: flex` on source order.

## Layout

- Square corners, with two exceptions — the chamfer and the arch, both defined
  under [The sheet](#the-sheet). Radius scale tops out at `16px`. The old
  3.5rem/2.5rem card radii read as bubble-gum, not as the work of a practice
  that details buildings.
- **A labelled action is a plate; a bare icon target is a dot.** Buttons,
  submits and filter chips take `rounded-xs` (2px) — a machined edge.
  `rounded-pill` survives only where the control *is* a circle: the floating
  contact buttons, the lightbox and carousel controls, spinners. Everything on
  this site is square, arched or chamfered; a pill on a labelled button was a
  product convention borrowed into an architectural one, and it read as
  borrowed the moment a classical serif started carrying the headings.
- **Hairlines over boxes.** Sections separate with a 1px `--c-line` rule.
  Cards are used only where a card is genuinely the affordance, and never nested.
- Semantic z-index: `base → raised → sticky → header → overlay → modal → toast
  → tooltip`. No arbitrary `z-[9999]`.
- Grids are `sm:grid-cols-2 lg:grid-cols-3` with `gap-x-8 gap-y-12`.
- **Both directions are the design.** Use logical properties (`start-*`, `end-*`,
  `ms-*`, `ps-*`) and let them mirror; do not pair them with an `isArabic`
  conditional, which flips twice and cancels out.

## The sheet

One rule governs every frame on this site:

> **Portrait images arch. Wide plates chamfer.**

- The **arch** (`.arch`) is elliptical corners on the top two only:
  `50% 50% 0 0 / var(--arch-rise, 22%) …`. It is the most legible architectural
  form there is, it is native to the contemporary interiors this practice
  actually builds in Kuwait and Cairo, and — alone among the shapes here — it is
  symmetric, so it needs no RTL handling at all. Corners, not `clip-path`, so
  `overflow: hidden` clips the photograph to the same curve and the shape
  survives alongside the wipe reveal. **`--arch-rise` is 22% everywhere.** This
  is the one radius that breaks the 16px ceiling below; it is a form, not a
  rounded corner.
- The **chamfer** is a 45° cut at the inline end, traced by a gold hairline:
  `.aperture-frame` > `.aperture` + `.aperture-edge`. Size it to the plate with
  `--chamfer` — 2.25rem on the header's title block, 3.25rem on a page banner.
  A 2rem cut on a 1200px band reads as a nick.
- The **title block** is a canvas plate cut into a frame's inline-start base,
  carrying a mark. On a service card it holds that service's drawing; on a page
  header it holds the heading itself. It is the reason type and marks always
  have a guaranteed background instead of sitting on an arbitrary photograph.
- The **drawn counterpart**: on the About surfaces a second arch is struck
  behind the photograph in a gold hairline, offset up and outward — the opening
  as designed against the opening as built.
- A **dimension line** (`.dimension`): a span with a gold tick at each end, and
  an annotation under each tick — the action at the inline start, a real
  quantity at the end. It is the most recognisable annotation in engineering
  drawing, and unlike a decorative marker it measures something. Drop a
  `.sweep-rail` inside and the plotter line runs along it.
- A **setting-out grid** (`.grid-draft`) on ink surfaces only — gold at 0.08,
  4.5rem module. Verified: canvas text over a grid line still measures 15.7:1.

`clip-path` and gradients take no logical values, so both directions of the
chamfer, the wipe and every ambient loop are written out longhand in `app.css`.
Never derive them from an `isArabic` conditional — `start-*`/`end-*` are
already direction-aware, and pairing the two flips twice and cancels out.

### The hero splits at `lg`

Below `lg` the hero is a photographic plate with the copy on canvas beneath it
— the same object every page header is. Above `lg` the plate fills the section
and the copy sits on it behind the horizontal scrim.

That is the site's own rule applied where it was being broken. A phone is too
narrow for a side scrim, so the previous build reached for a vertical one at
0.82–0.96 alpha: it guaranteed the contrast and erased the photograph doing it.
A practice that sells rooms cannot show its rooms at four percent. **There is
deliberately no mobile scrim** — nothing is over the image to protect.

### The page header

The heading and the photograph are **one object**. The plate carries the image;
the title sits in a canvas block cut into its bottom inline-start corner, with
its own chamfer opening onto the photograph. Two things follow:

- Type is on solid canvas, so it measures 17.6:1 regardless of the image. An
  overlaid heading can never promise that.
- Pass no `image` and the block stands on its own — the header degrades to a
  plain heading rather than pretending to overlap something.
- **The block is narrower than the plate at every width**, phones included. Run
  it full-bleed and it stops being a corner cut and becomes a bar across the
  bottom of the photograph: the chamfer needs a corner to cut into, and the
  image has to keep showing past the block's trailing edge.

### The detail pages lead with the action

On `ProjectDetailView` and `ServiceDetailView` the aside — the brief, the
enquiry card, the button — comes **first in the document**, and explicit grid
placement (`lg:col-start-9 lg:row-start-1`) puts it back in the right-hand
column from `lg`. It used to sit under the full description and every gallery
frame: on a phone, the one action on the page, three screens down.

Ordering it in the DOM rather than with CSS `order` keeps reading order and
visual order the same on the devices where it matters most.

## Imagery

The photograph leads. Every card and detail page opens on an image; type sits
under it, on canvas, never floating over an arbitrary photo.

- All bundled photography is **self-hosted** under `public/images/` at
  responsive widths (hero 960/1600/2400, about 800/1400, services 640/1080,
  projects 800/1600, banners 1200/2000). Provenance in
  `public/images/CREDITS.txt`.
- Remote images always route through `AppImage.vue`, which steps a failed load
  down a chain: primary source → local stand-in → placeholder mark. A broken
  `<img>` otherwise renders its alt text at body size, over whatever is layered
  on top of it.
- **Project covers fall back by category** (`projectImage()` in `lib/media.js`),
  picked deterministically from the record id so the grid never reshuffles. The
  studio's own covers point at a WordPress domain that no longer resolves, so
  this pool is what the grid actually shows today. They are stand-ins: the
  moment a real cover loads, the pool is never reached.
- **Gallery frames fall back the same way** (`projectGalleryImage()`), walking
  the whole pool and offset by the record id, so a six-frame gallery does not
  repeat one photograph six times and two projects opened side by side do not
  show an identical set. The lightbox routes through `AppImage` for the same
  reason — a bare `<img>` there rendered the alt string in white on ink and
  called it a photograph.
- **The brand lockup is `public/images/brand/lockup.png`.** The uploaded logo
  was a 1024px square JPEG with a flat grey field baked in — a social avatar,
  which in a header renders as a tile with a hard edge against the canvas. That
  file is the same artwork with the field lifted off and the ink trimmed to its
  own bounds. The `logo` setting points at it; re-uploading in the dashboard
  still overrides it, and `storage/settings/logo.jpg` is untouched.
- Service records that still reference an off-site host are repointed with
  `php artisan media:localise`. Do **not** re-run `ServiceSeeder` to fix an
  image — it opens with `Service::truncate()`.
- Every inner page opens on a plate (`PageHeader`'s `image` prop), capped at
  3:1 on desktop. Frames whose subject sits outside the middle third pass an
  `object-position` through `image.position`.

### About sections size themselves to the copy

`AboutFigure.vue` is a **pair**, not a picture: an arched main photograph and a
square detail plate matted into its base — the workshop and the tools, the room
and the stone, the structure and the drawing of it. The pair is what makes each
section specific rather than generic.

The main photograph takes its height from the paragraph beside it: the section
grid is `lg:items-stretch`, the figure is `lg:h-full`, and the image drops its
aspect ratio for `lg:h-full lg:min-h-[30rem] object-cover`. **Do not put an
aspect ratio back on it at `lg`.** A fixed ratio is what left every section
with an image that did not line up with its own text, and it goes wrong again
the moment the studio edits the copy. The `min-h` is the floor for a short
section; nothing sets a ceiling.
- **Text over photography requires a scrim that guarantees the minimum ratio
  against the worst-case frame**, not the average one. The hero's is sized to
  the text column: 0.92 alpha out to 54%, matching `lg:max-w-[54%]` on the copy.
  Change one and you must change the other.
- Alt text describes the room, in both languages. It is voice, not compliance.

## Icons

One system: `EaiIcon.vue` + `icons.data.js`. 34 marks.

- 24×24 grid, **1.25 stroke**, **square caps, mitred joins** — drafting
  convention, and the reason these read as architectural rather than as generic
  UI icons. Do not round them.
- `currentColor` throughout, so an icon inherits its container's token.
- Eight **service marks** are small architectural drawings (an office plan with a
  door swing, a sawtooth industrial section, a bed elevation), not pictograms.
  Verified legible down to 18px.
- Social marks are the deliberate exception: real brand geometry, filled,
  because users identify them by silhouette.

This replaced four systems running at once — pasted Heroicons paths, Lucide,
Font Awesome from a CDN, and raster PNGs recoloured with a
`brightness-0 invert` filter hack.

## Motion

Ease-out only (`--ease-out-quart`, `--ease-out-expo`); no bounce, no elastic.
Durations `--dur-fast|base|slow|reveal` (180/320/620/900ms).

- Transform, opacity and `clip-path` only. Never animate `width`, `height`, or
  spacing. Since the depth layer landed, `translate` and `scale` — the
  individual transform properties — count as transform and are preferred
  wherever two effects have to share one element.
- Direction-dependent motion uses `--dir-angle` / `--dir-sign` / `--dir-origin`
  from `app.css`.
- Every animation has a `prefers-reduced-motion: reduce` path, and the hero
  carousel does not autoplay at all under it.

Four movements, each tied to what it reveals — not one entrance applied to
every section:

| Movement | Where | Why |
|---|---|---|
| `v-reveal` (rise) | headings, copy, stat rows | settles the section into place |
| `v-reveal:rule` | the gold hairlines above headings | a rule is drawn, not faded |
| `v-reveal:wipe` | every photograph | a fade on an image reads as loading |
| `EaiIcon` `draw` | service and capability marks | these are drawings, so they draw |

`draw` dashes each `<path>` to its own `getTotalLength()`, so all strokes finish
together instead of the short ones snapping shut early.

## Depth

This practice sells space. A site that sells space and renders as a stack of
flat rectangles is arguing against itself, so the page has a Z axis.

The third dimension here is **space, not objects** — no floating shapes, no
spinning props. An interior architect's own 3D is the axonometric: the drawing
convention that shows a room's three dimensions at once with no perspective
distortion, because a drawing has to stay measurable. Everything below is either
that, or the depth a photograph gets when its frame becomes an opening.

### Three rules

**1. The JS writes numbers, not transforms.** `v-depth` publishes `--depth`
(-1..+1 through the viewport); `v-tilt` publishes `--tx`/`--ty` (the cursor over
the element). Every visual consequence is declared in `app.css`. That is what
lets one element carry a per-frame scroll parallax, a 620ms hover zoom and a
press state at once — three owners of movement that would otherwise be fighting
over a single `transform` slot.

**2. Depth is never load-bearing.** Both properties default to `0`, so an
element whose directive declines to run — reduced motion, coarse pointer, no
observer, headless — sits exactly where the flat stylesheet put it. Same
contract as the reveal: it enhances, it never positions.

**3. `perspective()` in the transform, `perspective` only on a card.** An
ancestor with the `perspective` *property* becomes the containing block for
every `position: fixed` descendant — which would drop the gallery lightbox into
the middle of the page the moment it opened. The route transition therefore uses
the function form only. On a tilt card both appear, and they are not
interchangeable: the function projects the element's own rotation, the property
projects its children's. `transform-style` stays `flat` there on purpose —
`preserve-3d` composes the parent's matrix (perspective function included) onto
the child a second time and the lift arrives doubled.

### What moves

| Utility | Applied to | Movement |
|---|---|---|
| `.set-plate` | project, service and gallery frames | tips into place as it crosses the screen — the scroll's own 3D |
| `.plate-img` | every photograph in a clipped frame | drifts ±3% against the scroll inside its own frame |
| `[data-tilt]` | project, service and about figures | ±4.5deg toward the cursor, ~80ms of lag |
| `.tilt-lift` | the frame inside a tilt card | rides 26px forward off the caption |
| `.sheen` | inside the frame | a canvas-white highlight that follows the cursor |
| `.depth-lede` | the hero copy only | runs ~1.5 lines ahead of the scroll |
| `.hero-dolly` | the hero photograph | pushes in 5.5% as the copy runs out |
| `.depth-plane` | the ghost arch behind an about figure | lags the built one, so drawn and built separate |
| `.page-enter/leave` | route change | the old page recedes, the new one comes forward |

**The set is the scroll's own third dimension.** A card is not a rectangle that
scrolls past: low on the screen it lies back with its far edge away from you, at
the middle it is flat and closest, leaving at the top it tips the other way. The
recession is `--depth` **squared**, so it is symmetric about the centre — every
plate is furthest at both ends of its travel and nearest when you are actually
looking at it. Because the orientation is a function of *position* and not of a
fired event, scrolling back up runs it backwards, which a one-shot reveal can
never do.

When a plate carries `.set-plate` **and** `v-tilt`, the two orientations add:
the cursor pushes the plate from wherever the scroll has already left it, so the
*change* on hover is always ±4.5deg relative to rest. Both live in one
`transform` because they land on one element and `transform` is one slot —
`[data-tilt]` is written last on purpose, and it is the rule that wins.

`.hero-dolly` is a `scale` rather than a translate, deliberately: the hero plate
is full-bleed with nothing to clip it, so displacement would show an edge while
a scale only ever crops. It composes with the Ken Burns instead of fighting it —
that loop owns `transform`, this owns `scale`, and the two multiply.

`--plate-scale: 1.1` and `--plate-travel: 3%` are arithmetic, not taste: the
scale has to cover twice the travel before the frame's edge shows through
(1.06 is the floor). Every point above that is a permanent crop off a
photograph this practice is being judged on — which is why the drift is small.

> `.tilt-lift` and `.plate-img` must never land on the same element. Each owns a
> `transition`, and the later rule wins outright and silently drops the other's
> easing. The frame lifts, the photograph inside it drifts: keep them one level
> apart.

**The phone is not left flat.** `v-tilt` runs only under
`(hover: hover) and (pointer: fine)` — a rotation driven by a finger that is
already covering the card is a rotation nobody can see. The touch story is
`v-depth`, which needs no pointer at all, so every photograph on a phone still
has a room behind it.

### The drawing

`DraftingRoom.vue` is the one signature. It draws an axonometric room — floor
grid, two walls, a door, a window, a dropped soffit, two pieces of massing and a
dimension line — stroke by stroke, the way a plotter lays down a sheet. It sits
behind `CtaBanner`, which closes every page but Contact, so it is the last thing
a visitor sees before deciding whether to write.

**Two rotations on one axis, added rather than chosen between.** The 19s sway is
the room breathing on its own; the scroll term is the visitor walking around it.
Together they keep the drawing alive on a screen nobody is touching and spatial
on one somebody is reading.

> The azimuth can reach `angle ± (sway + scrollTurn)`, and an axonometric only
> reads between roughly 20 and 60 degrees — outside that band the room turns
> face-on and collapses into overlapping rectangles. `40 ± 18` keeps the whole
> range inside it. Those three numbers are one decision; do not move one alone.
> The fit samples nine azimuths across the range for the same reason: a
> silhouette is widest somewhere in the middle of a swing, not at its ends.

**The section sweep** is the one ambient loop on this site doing real work
rather than decorating. Every 13s a vertical plane travels the room's long axis,
drawn where it meets the volume — full height and depth at that station, plus
the profile of whatever it passes through, which is why the fit-out is held as
volumes (`MASSING`) and not as loose edges. It runs for 42% of the cycle and is
absent for the rest: a cut that passes every thirteen seconds is a detail, one
that never leaves is a metronome. It waits for the draw-on to finish — a section
through a half-drawn room is noise.

- **Hand-built, not a 3D library.** The whole model is a few dozen edges and one
  rotation matrix. The alternative is several hundred kilobytes of renderer to
  draw lines, on a page whose hero photograph should own the network budget. It
  also keeps the output a *drawing* rather than a render, which is the point.
- Construction order is the draw order: sheet, floor, structure, openings,
  fit-out, dimensions. Depth drives stroke alpha, which is what stops a
  wireframe collapsing into a flat tangle.
- The gold is read out of `--c-gold` at runtime, with an sRGB fallback probed by
  assigning `oklch()` to `strokeStyle` and checking whether it took.
- It pauses off-screen and on a hidden tab, and under reduced motion it paints
  once, finished, at its resting azimuth — no sway, no scroll coupling, no
  section. Verify with `scratchpad/room-check.mjs`, which fingerprints the
  canvas four times and reports `MOVING` or `still`.
- The `.cta-veil` gradient hands the reading side back to the type — the same
  directional device as the hero scrim, for the same reason. Below `sm` it keeps
  a floor across the full width instead: on a phone the copy and the room occupy
  the same column whatever the offset does, and legibility wins the screens
  where the two cannot be separated.

### The walk

`VillaWalk.vue` is the home page's centrepiece: five spaces of a finished
villa — the approach, the hall, the majlis, the family room, the terrace — in
the order you would actually be shown them, and you scroll through them.

**Why not WebGL.** The brief is "make scrolling feel like moving through a
space", and the genre answer is a 3D model. Here that is the wrong answer twice
over: several hundred kilobytes of renderer, and a procedural room that would
look worse than the photographs it stood in front of. This practice's product
IS the photography. So the camera moves through the photographs.

**The handover is a door, not a dissolve.** A timed crossfade is over in 200ms
and nobody studies the middle of it; a scroll-driven one can be parked on.
Measured across the runway, a crossfade between two full-bleed plates left the
covered one contributing 27% of the frame — a double exposure a visitor can stop
and look at. Darkening the outgoing room fixed the ghost and dimmed the
photograph you were still standing in.

So the arriving room is never transparent. It is revealed through an arched
opening that starts the size of a door and expands past your periphery: you see
*into* the next room through the doorway, then you walk through it. Two plates
are on screen and neither is ever a ghost — one fills the frame, the other fills
the door.

> The opening is drawn twice: once as a `clip-path` on the arriving plate, once
> as the gold hairline around it. They must land on the same curve, so both are
> computed from **one pixel measurement** of the door (`measureDoor`) — a
> percentage radius inside `inset()` resolves against a box the two do not
> share. `kEnd`, the multiple at which the opening has cleared the frame, is
> derived from the viewport's shape: a phone needs the door four and a half
> times its own height, a wide desktop five times its width.

Other calibrations worth not undoing: the plate is exactly full-bleed at `u = 0`
and never smaller (an arriving plate under 1.0 shows its own hard edge inside
the door); the aperture is eased `t^2.4`, because a doorway barely changes size
until you are almost at it and then leaves your vision at once; the label and
the plan marker lead the camera by `LEAD = 0.28`, because a room takes the frame
before its own `u` reaches 0.

**The plan is what makes it a house.** A drawing of the villa sits in the corner
with the space you are in filled, and a marker that walks the route
*continuously* — a plan that only updates on arrival is a legend, not a
position. It is a drawing, so it does not mirror in Arabic; only the block it
sits in follows the reading direction.

**The default is a photo essay.** `walk--live` — the only class that pins,
stacks or hides anything — is added on mount, by the same code that can undo it.
Without it the section is five photographs in flow, each with its visible
caption, under a real heading. That is what a crawler, a reduced-motion visitor
and a failed script all get. The caption is written visible and taken out of the
flow in live mode, never the reverse: an `sr-only` caption promoted on a
condition is invisible in exactly the state where nothing else is showing it.

Verify with `scratchpad/walk-probe.mjs`, which steps the whole runway and prints
each plate's state at every station.

### The page, measured

`ScrollMeasure.vue` is a dimension line down the leading gutter, not a progress
bar: a hairline with a **witness tick at each end** marking the extent of the
document and a travelling station tick showing where in that span you are. The
ticks are what make it a measurement rather than a fill — the same annotation
the drafting room carries and `.dimension` uses in the page body. The site
measures its own length in the notation it measures rooms in.

Desktop only. On a phone the tab bar owns the bottom edge and the gutters are a
few millimetres wide. `0.875rem` from the edge is measured against the tightest
case, not chosen: between 1024 and 1280 the container runs full width with 2rem
of padding, so content starts at 32px and this occupies 14–23px.

The rail fills with `scaleY`, and the station travels on `transform` against a
`--span` the component measures in **pixels** — a percentage there would resolve
against the tick's own 2px height, and animating `top` would put a layout
property on the frame loop.

### Ambient motion

Three loops never stop. They are what makes the site feel alive rather than
merely animated-on-scroll, and they are slow on purpose: at these durations the
eye reads them as the page breathing, not as something demanding attention.

| Loop | Where | Period |
|---|---|---|
| `MarkTicker` | home services block, Services page, About page | 48s |
| `.grid-draft--drift` | the ink CTA band | 26s |
| `.sweep-rail` | top edge of every page header plate | 7s |
| `DraftingRoom` sway | the ink CTA band | 19s |
| `DraftingRoom` section sweep | the ink CTA band | 13s |

All three travel **with the reading direction** via `--dir-sign`, all three are
pure `transform` on the compositor, and all three loop on an exact multiple of
their own pattern so there is never a visible jump.

The last two are documented in full under **Depth** below.

**All of them stop dead under `prefers-reduced-motion: reduce`.** A marquee that
still creeps is the worst thing this stylesheet could do to someone who asked
for less motion. Verify with `scratchpad/motion-check.mjs`, which samples the
computed transform twice under both media states, and with
`scratchpad/depth-probe.mjs`, which reports node counts and computed values for
the depth layer under fine pointer, coarse pointer and reduced motion.

### The reveal contract

**Reveals enhance an already-visible default.** The `pending` state — the only
one that hides anything — is written by `v-reveal` (`directives/reveal.js`) and
only after it has confirmed IntersectionObserver exists and reduced motion is
off. A 2.5s failsafe fires the reveal even if the observer never reports. So if
JS never runs, the tab stays hidden, or the page renders headless, the content
is simply there.

Never write a CSS rule that hides content by default and relies on a class to
bring it back. Check with:

```
document.querySelectorAll('[data-reveal="pending"]').length   // must reach 0
```

> **Do not write `:global([dir='rtl']) .thing` inside a component's
> `<style scoped>`.** Vue's scoped compiler rewrites it to a bare `[dir='rtl']`,
> dropping the descendant — which applied `transform: scaleX(-1)` and
> `translate(-100%)` directly to `<html>` and mirrored the entire Arabic site.
> Put direction logic in `app.css`.

## Banned here

On top of the shared bans: gradient-clipped text; white-on-gold; the tiny
uppercase tracked kicker above every section; `01 / 02 / 03` section markers
and their mono `// 03 +` variant — a Latin marker floating over an RTL layout
reads as debris, and numbering a section that is not part of a sequence is
scaffolding rather than voice (use `.dimension`, which carries information);
**`uppercase` on any string that also renders in Arabic** — it is a no-op
there, so the English shouts while the Arabic speaks normally (this is why the
footer's column headings carry weight and no tracking);
nested cards; icons in large rounded tiles above every heading; the floating
"15+ years" metric card; decorative blurred colour circles; `leading-[2]` on a
heading; text placed on an unscrimmed photograph.

Depth-specific: **3D that is objects rather than space** — floating cubes,
orbiting shapes, particle fields; a room's practice does not decorate with
props. `transform: scale()` on anything that also tilts or drifts (use the
`scale` property — a shorthand takes the whole slot); tilt angles past ~6deg,
where the crop visibly shears and a plate turns into a playing card;
`translateZ` on type, which rasterises a glyph at one size and displays it at
another; `perspective` as a property on any ancestor of a `position: fixed`
element; a WebGL renderer shipped to draw hairlines.

Mobile-specific: **an arch on a landscape frame** — the elliptical rise is a
percentage of the height, so on a wide box it flattens into two softly rounded
top corners and the site's one signature form disappears; two full-width pills
of equal weight where one is the primary action; a floating button that sits
under the tab bar; `100vh` (use `svh`/`dvh` — `100vh` is the viewport with the
browser chrome retracted, so it always overshoots by the height of the address
bar); an input under 16px, which makes iOS Safari zoom on focus and stay
zoomed.
