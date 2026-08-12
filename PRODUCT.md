# Product

## Register

brand

The public site (`resources/js/components/public/`, `resources/js/views/public/`) is the primary
surface and the one this file describes: it is a portfolio for a design practice, so the design
*is* the product. The admin dashboard (`resources/js/components/admin/`, `resources/js/views/admin/`)
is a **product**-register surface and should be judged by different rules — clarity, density and
task completion over expression. Do not apply brand-register moves there.

## Users

Two audiences, opposite postures:

- **Prospective clients** — owners and developers commissioning administrative, commercial,
  residential, exterior, hospitality, landscape, retail or industrial interiors. They arrive from a
  referral or an Instagram link, usually on a phone, usually already holding a competing quote.
  They are not reading. They are scanning for evidence that this practice has actually built things
  at their level of ambition. The job to be done is **"convince me you are the safe expensive
  choice."** Photography carries that conviction; text supports it.
- **The studio itself** — updating projects, services and site copy through the admin dashboard
  between site visits, often quickly and often on a laptop in poor conditions.

The site ships **Arabic and English** with real RTL layout (`localeStore`, `vue-i18n`, `[lang="ar"]`
CSS hooks). Arabic is not a translation layer bolted onto an English site — both directions are
first-class, and every layout decision has to survive mirroring.

## Product Purpose

Present the work of **Eslam Abdulghani Interiors** so that a serious commission feels like a low-risk
decision. Success is a contact-form submission or a direct call from someone who has looked at the
projects and concluded the practice is credible at their budget.

The site is a portfolio, not a catalogue. Its job is proof, not enumeration.

## Brand Personality

**Composed. Material. Exacting.**

The voice of a practice that does not need to raise its voice — the confidence is in the work being
shown, not in the adjectives around it. Restraint here is earned, not timid: every surface should
feel considered down to the hairline, in the way a well-detailed room does.

Emotionally the target is *assurance*, not excitement. The visitor should feel they are in careful
hands.

## Anti-references

- **The template-luxury interiors site.** Gold everything, script fonts, "LUXURY" in wide tracking,
  stock photos of unoccupied hotel lobbies. Gold used as decoration rather than as a material.
- **The AI landing page.** Cream canvas, tiny uppercase tracked eyebrow above every section,
  `01 / 02 / 03` markers, gradient text, identical rounded cards each with an icon in a rounded tile.
  The current build has drifted into several of these; the review is explicitly correcting it.
- **The editorial-magazine default.** Display serif + italic + drop caps + broadsheet rules. It is
  one aesthetic lane, and it is not this brand's — this is an *architectural* practice, not a journal.
- **SaaS product chrome.** Pill badges, soft drop shadows on everything, hero metric rows.

## Design Principles

1. **The photograph is the design.** Interiors are a visual trade. Imagery leads and typography
   defers to it; chrome that competes with a photograph loses. Zero-image sections are a bug.
2. **Gold is a material, not a highlight.** `#C5A880` behaves like aged brass in a real room —
   used sparingly, at edges and small fills, where a detail would actually be metal. It is never
   the colour of running text, because at 74.7% lightness it physically cannot carry text contrast.
3. **One voice per surface.** If every heading is black-weight uppercase, nothing is emphasised.
   Hierarchy comes from scale and space, not from shouting at every level at once.
4. **Detail at the hairline.** Precision reads at 1px — consistent rules, consistent icon stroke,
   consistent optical alignment. This is the level at which "expensive" is actually communicated.
5. **Both directions are the design.** Any composition that only works in LTR is unfinished.
   Mirroring is a first-class constraint, not a post-hoc fix.

## Accessibility & Inclusion

- **Target: WCAG 2.2 AA.** Body text ≥4.5:1, large text ≥3:1, UI boundaries ≥3:1 — verified
  numerically against the token ramp, not judged by eye.
- **Text is never placed on unscrimmed photography.** Where text overlays an image, the scrim must
  guarantee the minimum ratio against the *worst-case* frame, not the average one.
- **Gold is contrast-bound.** `--c-gold` is a surface/edge colour only. Text that needs to read as
  gold uses `--c-gold-deep`, and never over photography.
- **Reduced motion is honoured.** The hero auto-advances and several sections animate; every one of
  these needs a `prefers-reduced-motion: reduce` path, and the auto-advancing carousel must be
  pausable and keyboard reachable.
- **Arabic typography needs its own metrics.** Cairo sits differently from the Latin face; line
  height and tracking are set per-script, not shared.
