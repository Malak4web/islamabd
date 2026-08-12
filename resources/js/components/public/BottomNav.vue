<template>
  <!-- The phone's primary navigation.

       Every destination on this site is one of five, which is exactly the
       number a tab bar can hold — so the whole site map is one thumb-reach
       away and there is no drawer to open, no overlay to dismiss and no state
       to remember. That is the single change that makes this read as an
       application rather than as a website with a hamburger.

       Fixed, and permanently visible: navigation that hides itself on scroll
       saves 62px and costs the user the one thing they came to the bar for. -->
  <nav
    class="fixed inset-x-0 bottom-0 z-header border-t border-line bg-canvas/95 backdrop-blur-md supports-[backdrop-filter]:bg-canvas/85 lg:hidden"
    :aria-label="$t('common.primary_nav')"
  >
    <ul class="flex items-stretch" style="height: var(--tabbar-h)">
      <li v-for="tab in TABS" :key="tab.key" class="flex-1">
        <RouterLink
          :to="tab.path"
          class="tab press-sm group flex h-full flex-col items-center justify-center gap-1.5 focus-visible:outline-offset-[-3px]"
          :class="{ 'tab--on': isCurrent(tab) }"
          :aria-current="isCurrent(tab) ? 'page' : undefined"
          active-class=""
          exact-active-class=""
        >
          <!-- The active mark is a 1.5px gold rule on the edge facing the
               content — the same rule the desktop nav draws under its current
               link, on the side that faces the page. One indicator, stated
               twice in the same language. -->
          <span class="tab__rule" aria-hidden="true"></span>

          <EaiIcon :name="tab.icon" :size="21" class="tab__mark" />

          <span class="tab__label">{{ $t(`nav.tabs.${tab.key}`) }}</span>
        </RouterLink>
      </li>
    </ul>

    <!-- The home indicator on a modern iPhone. Zero everywhere else, so this
         costs nothing on the devices that do not have one. -->
    <div style="height: var(--safe-b)" aria-hidden="true"></div>
  </nav>
</template>

<script setup>
import { useRoute } from 'vue-router'
import EaiIcon from '@/components/icons/EaiIcon.vue'

/**
 * Order is the site's own reading order, not a popularity guess: the practice
 * introduces itself, states what it does, shows the work, then asks for the
 * call. Contact sits at the trailing edge because it is the end of that
 * sentence — and because in both languages the trailing edge is where a thumb
 * finishes.
 */
const TABS = [
  { key: 'home', path: '/', icon: 'home' },
  { key: 'about', path: '/about', icon: 'compass' },
  { key: 'services', path: '/services', icon: 'layers' },
  { key: 'projects', path: '/projects', icon: 'grid' },
  { key: 'contact', path: '/contact', icon: 'mail' },
]

const route = useRoute()

/**
 * Written out rather than left to RouterLink's own active classes, because
 * neither of them describes what a tab bar means.
 *
 * `active-class` matches on prefix, and `/` is a prefix of everything — the
 * home tab would be lit on every page. `exact-active-class` matches only the
 * exact path, and `/projects/7` is a different route record from `/projects`,
 * so opening a project would put out the tab you opened it from. A tab bar
 * says "you are in this section", and staying lit through a section's detail
 * screens is the whole of that promise.
 */
const isCurrent = (tab) => {
  const path = route?.path ?? '/'
  if (tab.path === '/') return path === '/'
  return path === tab.path || path.startsWith(`${tab.path}/`)
}
</script>

<style scoped>
.tab {
  position: relative;
  color: oklch(var(--c-ink-subtle));
  transition: color var(--dur-fast) var(--ease-out-quart);
}

/* 5.5:1 at rest, 17.6:1 when current. Colour is never the only cue — the rule
   above the tab carries the state too, for anyone who cannot separate the
   two golds from the two greys. */
.tab--on {
  color: oklch(var(--c-ink));
}

.tab__rule {
  position: absolute;
  top: -1px;
  inset-inline: 22%;
  height: 1.5px;
  background-color: oklch(var(--c-gold-deep));
  transform: scaleX(0);
  transition: transform var(--dur-base) var(--ease-out-quart);
}
.tab--on .tab__rule {
  transform: scaleX(1);
}

.tab__mark {
  transition: transform var(--dur-base) var(--ease-out-quart);
}
.tab--on .tab__mark {
  color: oklch(var(--c-gold-deep));
}

.tab__label {
  font-size: 0.6875rem;
  line-height: 1;
  font-weight: 500;
  /* No tracking. Latin at this size would take a little, but Arabic must take
     none — letter-spacing breaks the joins between glyphs — and a label set
     two different ways in two languages is two designs. */
  letter-spacing: 0;
  /* A label that truncates is worse than a shorter word. The strings in
     `nav.tabs` are chosen to fit a fifth of a 320px viewport in both
     languages; this is the guard, not the plan. */
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.tab--on .tab__label {
  font-weight: 600;
}

@media (prefers-reduced-motion: reduce) {
  .tab,
  .tab__rule,
  .tab__mark {
    transition: none;
  }
}
</style>
