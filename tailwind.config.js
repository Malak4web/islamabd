/** @type {import('tailwindcss').Config} */

/**
 * Every colour resolves to a token declared in resources/css/app.css.
 * The `<alpha-value>` placeholder lets `bg-canvas/85`, `text-ink/60` etc.
 * work against OKLCH values.
 *
 * Naming is semantic (canvas / ink / gold), never literal (cream / charcoal),
 * so the ramp can be retuned without renaming a single class in a component.
 */
const token = (name) => `oklch(var(--c-${name}) / <alpha-value>)`

export default {
  content: [
    './resources/**/*.{vue,js,ts,blade.php}',
  ],
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ['var(--font-display)'],
        sans: ['var(--font-body)'],
        arabic: ['var(--font-arabic)'],
      },

      colors: {
        canvas: {
          DEFAULT: token('canvas'),
          raised: token('surface'),
          inset: token('canvas-inset'),
        },
        ink: {
          DEFAULT: token('ink'),
          muted: token('ink-muted'),
          subtle: token('ink-subtle'),
        },
        line: {
          DEFAULT: token('line'),
          strong: token('line-strong'),
        },
        gold: {
          DEFAULT: token('gold'),
          deep: token('gold-deep'),
          soft: token('gold-soft'),
          wash: token('gold-wash'),
        },
        positive: token('positive'),
        critical: token('critical'),
        notice: token('notice'),
      },

      /**
       * Modular scale, ratio ~1.25, fluid between 360px and 1440px viewports.
       *
       * Retuned for the display face. Cormorant Garamond is an old-style
       * serif with a small x-height, so it sets roughly a tenth smaller than
       * Inter at the same value — the display steps are up by that much, and
       * `display` still lands under the 6rem ceiling. Above that a page is
       * shouting rather than designing.
       *
       * The tracking went with it. Inter needed -0.03em at display sizes to
       * stop looking loose; a serif's own serifs already close those gaps, and
       * the same value on Cormorant's fine hairlines pushes them into each
       * other. Display sizes sit near zero now, and only the two smallest
       * steps — both set in Inter — keep any tracking at all.
       */
      fontSize: {
        'display': ['clamp(3rem, 1.75rem + 5.6vw, 5.9rem)',    { lineHeight: '1.02', letterSpacing: '-0.012em' }],
        'title':   ['clamp(2.2rem, 1.5rem + 3.1vw, 3.85rem)',  { lineHeight: '1.1',  letterSpacing: '-0.008em' }],
        'heading': ['clamp(1.65rem, 1.3rem + 1.5vw, 2.45rem)', { lineHeight: '1.2',  letterSpacing: '-0.005em' }],
        'subhead': ['clamp(1.3rem, 1.15rem + 0.55vw, 1.6rem)', { lineHeight: '1.3',  letterSpacing: '0' }],
        'lede':    ['clamp(1.0625rem, 1rem + 0.3vw, 1.25rem)', { lineHeight: '1.65' }],
        'label':   ['0.8125rem', { lineHeight: '1.35', letterSpacing: '0.02em' }],
      },

      /**
       * Floor is -0.03em on display sizes (set above). The general `tight`
       * step exists so components stop reaching for Tailwind's `tracking-tighter`
       * (-0.05em), which collides glyphs at large sizes.
       */
      letterSpacing: {
        tight: '-0.02em',
        normal: '0',
        wide: '0.04em',
        label: '0.08em',
      },

      /**
       * Architectural, not bubble-gum. The old build used 3.5rem (56px) on
       * cards and 2.5rem on icon tiles; nothing in a detailed interior is that
       * round. `pill` is reserved for buttons.
       */
      borderRadius: {
        none: '0',
        xs: '2px',
        sm: '3px',
        DEFAULT: '4px',
        md: '6px',
        lg: '10px',
        xl: '16px',
        pill: '999px',
      },

      /**
       * Semantic stack. Replaces the arbitrary z-20 / z-50 / z-[999] values
       * that were scattered across components.
       */
      zIndex: {
        base: '0',
        raised: '10',
        sticky: '20',
        header: '30',
        overlay: '40',
        modal: '50',
        toast: '60',
        tooltip: '70',
      },

      /**
       * Shadows are near-neutral and low-contrast: a warm-tinted shadow on a
       * warm canvas reads as smudge. These are for elevation only, never decoration.
       */
      boxShadow: {
        hairline: '0 0 0 1px oklch(var(--c-line) / 1)',
        raise: '0 1px 2px oklch(var(--c-ink) / 0.04), 0 4px 12px oklch(var(--c-ink) / 0.05)',
        lift: '0 2px 4px oklch(var(--c-ink) / 0.05), 0 12px 28px oklch(var(--c-ink) / 0.08)',
        modal: '0 8px 24px oklch(var(--c-ink) / 0.12), 0 32px 64px oklch(var(--c-ink) / 0.14)',
        none: 'none',
      },

      transitionTimingFunction: {
        'out-quart': 'var(--ease-out-quart)',
        'out-expo': 'var(--ease-out-expo)',
      },

      transitionDuration: {
        fast: '180ms',
        base: '320ms',
        slow: '620ms',
      },

      maxWidth: {
        prose: '68ch',    /* inside the 65–75ch legibility band */
        measure: '46ch',  /* short intros, hero paragraphs */
      },
    },
  },
}
