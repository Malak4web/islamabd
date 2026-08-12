<template>
  <nav :class="containerClass">
    <RouterLink
      v-for="link in links"
      :key="link.key"
      :to="link.path"
      :class="linkClass || defaultLinkClass"
      :active-class="linkClass ? undefined : 'nav-link--active'"
      @click="$emit('linkClick')"
    >
      {{ $t(`nav.${link.key}`) }}
      <!-- The underline is the active indicator. Colour alone was carrying it
           before, in a gold that measures 2.1:1 against the canvas — invisible
           to anyone reading in daylight, and the only cue for the current page. -->
      <span v-if="!linkClass" class="nav-link__rule" aria-hidden="true"></span>
    </RouterLink>
  </nav>
</template>

<script setup>
defineProps({
  containerClass: { type: String, default: 'flex items-center gap-8' },
  linkClass: { type: String, default: '' },
})

defineEmits(['linkClick'])

const defaultLinkClass = 'nav-link'

const links = [
  { path: '/', key: 'home' },
  { path: '/about', key: 'about' },
  { path: '/services', key: 'services' },
  { path: '/projects', key: 'projects' },
  { path: '/contact', key: 'contact' },
]
</script>

<style scoped>
.nav-link {
  position: relative;
  font-size: 0.8125rem;
  font-weight: 500;
  letter-spacing: 0.02em;
  color: oklch(var(--c-ink-muted));
  transition: color var(--dur-fast) var(--ease-out-quart);
  padding-block: 0.25rem;
}
.nav-link:hover {
  color: oklch(var(--c-ink));
}

/* Scales rather than animating `width`: a width transition relayouts the nav
   on every hover, and this runs on the compositor instead. */
.nav-link__rule {
  position: absolute;
  inset-inline: 0;
  bottom: -2px;
  height: 1.5px;
  background-color: oklch(var(--c-gold-deep));
  transform: scaleX(0);
  transform-origin: var(--dir-origin) 50%;
  transition: transform var(--dur-base) var(--ease-out-quart);
}
.nav-link:hover .nav-link__rule {
  transform: scaleX(1);
}

.nav-link--active {
  color: oklch(var(--c-ink));
  font-weight: 600;
}
.nav-link--active .nav-link__rule {
  transform: scaleX(1);
}

@media (prefers-reduced-motion: reduce) {
  .nav-link__rule {
    transition: none;
  }
}
</style>
