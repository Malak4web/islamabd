<template>
  <div v-if="projectStore.loading && !project" class="flex min-h-[62svh] items-center justify-center bg-canvas">
    <span class="sr-only">{{ $t('common.loading') }}</span>
    <div class="h-10 w-10 animate-spin rounded-pill border-2 border-line border-t-gold-deep" role="status"></div>
  </div>

  <main v-else-if="project" class="min-h-[62svh] bg-canvas">
    <!-- Cover. The title used to sit ON the photograph behind a gradient that
         faded to transparent through the middle — charcoal text over whatever
         the cover happened to be. It sits under the image now, on canvas. -->
    <!-- Clears the fixed header, which is solid off the home hero. Below `lg`
         the app shell already reserves the bar's height, so this applies only
         where the desktop header floats. -->
    <!-- `overflow-hidden` is load-bearing here, not tidiness: the cover carries
         the scroll parallax, which oversizes the image past its own frame, and
         a full-bleed plate with nothing to clip it hands the document a
         horizontal scrollbar. -->
    <figure v-depth class="overflow-hidden bg-canvas-inset lg:pt-[5.75rem]">
      <AppImage
        :src="project.cover_image"
        :fallback-src="cover.src"
        :fallback-srcset="cover.srcset"
        sizes="100vw"
        :alt="title"
        eager
        :placeholder-size="64"
        img-class="plate-img h-[44svh] min-h-[16rem] w-full object-cover sm:h-[58svh] lg:h-[64svh]"
      />
    </figure>

    <header class="border-b border-line">
      <div class="container mx-auto px-6 py-10 sm:px-8 sm:py-14">
        <p v-if="category" class="text-label font-semibold capitalize text-gold-deep">{{ category }}</p>
        <h1 class="mt-3 max-w-4xl text-title font-light text-ink">{{ title }}</h1>
      </div>
    </header>

    <section class="py-12 sm:py-20">
      <div class="container mx-auto px-6 sm:px-8">
        <!-- The brief comes FIRST in the document.

             It was last: on a phone that put the facts a visitor came for —
             where it is, what year, and the button that starts a conversation
             — below the full description and every gallery frame, three or
             four screens down. Ordering it here fixes that without splitting
             visual order from reading order, which reordering by CSS alone
             would have done. From `lg` explicit grid placement puts it back in
             the right-hand column, on the same row as the copy. -->
        <div class="grid items-start gap-10 lg:grid-cols-12 lg:gap-16">
          <aside class="lg:col-span-4 lg:col-start-9 lg:row-start-1 lg:sticky lg:top-28">
            <div class="border border-line bg-canvas-raised p-6 sm:p-8">
              <h2 class="border-b border-line pb-4 text-subhead font-medium text-ink sm:pb-5">
                {{ $t('projects.brief') }}
              </h2>

              <!-- Two columns of facts on a phone: three stacked rows of one
                   short value each wastes a screen the visitor has to scroll
                   past to reach the action. -->
              <!-- Not every record carries these. An empty <dl> still takes
                   its margin, which reads as a gap the layout forgot to
                   fill. -->
              <dl
                v-if="project.client || project.location || project.year"
                class="mt-5 grid grid-cols-2 gap-x-6 gap-y-5 sm:mt-6 lg:grid-cols-1"
              >
                <div v-if="project.client" class="col-span-2 lg:col-span-1">
                  <dt class="text-label text-ink-subtle">{{ $t('projects.client') }}</dt>
                  <dd class="mt-1 text-ink">{{ project.client }}</dd>
                </div>
                <div v-if="project.location">
                  <dt class="text-label text-ink-subtle">{{ $t('projects.location') }}</dt>
                  <dd class="mt-1 text-ink">{{ project.location }}</dd>
                </div>
                <div v-if="project.year">
                  <dt class="text-label text-ink-subtle">{{ $t('projects.year') }}</dt>
                  <dd class="mt-1 text-ink">{{ project.year }}</dd>
                </div>
              </dl>

              <RouterLink
                to="/contact"
                class="press mt-7 flex items-center justify-center gap-2.5 rounded-xs bg-gold px-6 py-4 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft sm:mt-8"
              >
                {{ $t('projects.inquire') }}
                <EaiIcon name="arrow-right" :size="16" flip />
              </RouterLink>
            </div>
          </aside>

          <div class="min-w-0 lg:col-span-8 lg:col-start-1 lg:row-start-1">
            <h2 class="text-label font-semibold text-gold-deep">{{ $t('projects.concept') }}</h2>
            <p class="mt-4 max-w-prose whitespace-pre-line text-lede text-ink-muted">
              {{ description }}
            </p>

            <div v-if="gallery.length" class="mt-12 sm:mt-14">
              <h2 class="text-label font-semibold text-gold-deep">{{ $t('projects.journey') }}</h2>

              <!-- A rail on a phone, a grid from `sm`: swiping through frames
                   is how a photo set is read on a phone, and it keeps each
                   frame large enough to judge instead of shrinking them to fit
                   a column. Tapping still opens the lightbox. -->
              <div class="rail-sm rail-bleed mt-5 gap-4 pb-2 sm:grid sm:grid-cols-2">
                <!-- A real <button>, so the lightbox is reachable by keyboard.
                     These were clickable <div>s with no role and no tabindex. -->
                <button
                  v-for="(img, idx) in gallery"
                  :key="idx"
                  v-depth
                  v-reveal:wipe="{ delay: idx * 70 }"
                  type="button"
                  class="press set-plate group relative block w-[86%] overflow-hidden bg-canvas-inset focus-visible:outline-offset-4 sm:w-auto [--set-max:4deg]"
                  :aria-label="$t('projects.open_image', { n: idx + 1 })"
                  @click="openLightbox(idx)"
                >
                  <AppImage
                    :src="img"
                    :fallback-src="galleryFallback(idx).src"
                    :fallback-srcset="galleryFallback(idx).srcset"
                    sizes="(min-width: 640px) 33vw, 86vw"
                    :alt="`${title} — ${idx + 1}`"
                    img-class="plate-img aspect-[4/3] w-full object-cover"
                  />
                  <span
                    class="absolute inset-0 flex items-center justify-center bg-ink/35 opacity-0 transition-opacity duration-base group-hover:opacity-100 group-focus-visible:opacity-100"
                  >
                    <span class="flex h-12 w-12 items-center justify-center rounded-pill bg-canvas text-ink">
                      <EaiIcon name="expand" :size="20" />
                    </span>
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Lightbox -->
    <Transition name="fade">
      <div
        v-if="lightbox.isOpen"
        ref="lightboxEl"
        class="fixed inset-0 z-modal flex flex-col bg-ink/95"
        role="dialog"
        aria-modal="true"
        :aria-label="$t('projects.journey')"
      >
        <div class="flex items-center justify-between px-4 py-4 sm:px-8">
          <span class="text-label text-canvas/70">
            {{ lightbox.index + 1 }} / {{ gallery.length }}
          </span>
          <button
            ref="lightboxClose"
            type="button"
            class="flex h-11 w-11 items-center justify-center rounded-pill text-canvas transition-colors duration-fast hover:bg-canvas/15"
            :aria-label="$t('common.close')"
            @click="closeLightbox"
          >
            <EaiIcon name="close" :size="22" />
          </button>
        </div>

        <!-- Swipe advances the frame. It is the gesture a phone gallery is
             expected to answer, and it is additive: the arrows stay, they stay
             keyboard-reachable, and the arrow keys still work. A gesture that
             is the *only* way to do something is the thing to avoid. -->
        <div
          class="flex min-h-0 flex-1 touch-pan-y items-center gap-2 px-2 pb-6 sm:gap-4 sm:px-6"
          @touchstart.passive="onTouchStart"
          @touchend.passive="onTouchEnd"
        >
          <button
            v-if="gallery.length > 1"
            type="button"
            class="press hidden h-12 w-12 flex-none items-center justify-center rounded-pill text-canvas transition-colors duration-fast hover:bg-canvas/15 sm:flex"
            :aria-label="$t('hero.previous')"
            @click="prevSlide"
          >
            <EaiIcon name="arrow-left" :size="22" flip />
          </button>

          <!-- Through AppImage, so a dead source steps down to the same
               stand-in the thumbnail is already showing. A plain <img> here
               rendered the alt string in white-on-ink and called it a photo. -->
          <AppImage
            :src="gallery[lightbox.index]"
            :fallback-src="galleryFallback(lightbox.index).src"
            :fallback-srcset="galleryFallback(lightbox.index).srcset"
            sizes="100vw"
            :alt="`${title} — ${lightbox.index + 1}`"
            eager
            img-class="mx-auto max-h-full min-h-0 w-auto max-w-full flex-1 object-contain"
          />

          <button
            v-if="gallery.length > 1"
            type="button"
            class="press hidden h-12 w-12 flex-none items-center justify-center rounded-pill text-canvas transition-colors duration-fast hover:bg-canvas/15 sm:flex"
            :aria-label="$t('hero.next')"
            @click="nextSlide"
          >
            <EaiIcon name="arrow-right" :size="22" flip />
          </button>
        </div>

        <!-- On a phone the arrows move below the frame, at thumb height, where
             they are reachable one-handed instead of pinned to the screen's
             far edges. -->
        <div
          v-if="gallery.length > 1"
          class="flex items-center justify-center gap-3 pb-8 sm:hidden"
        >
          <button
            type="button"
            class="press flex h-12 w-12 items-center justify-center rounded-pill border border-canvas/25 text-canvas"
            :aria-label="$t('hero.previous')"
            @click="prevSlide"
          >
            <EaiIcon name="arrow-left" :size="22" flip />
          </button>
          <button
            type="button"
            class="press flex h-12 w-12 items-center justify-center rounded-pill border border-canvas/25 text-canvas"
            :aria-label="$t('hero.next')"
            @click="nextSlide"
          >
            <EaiIcon name="arrow-right" :size="22" flip />
          </button>
        </div>
      </div>
    </Transition>

    <CtaBanner />
  </main>
</template>

<script setup>
import { reactive, ref, onMounted, onUnmounted, computed, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import { useProjectStore } from '@/stores/projectStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import CtaBanner from '@/components/public/CtaBanner.vue'
import AppImage from '@/components/public/AppImage.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { projectImage, projectGalleryImage } from '@/lib/media'

const route = useRoute()
const projectStore = useProjectStore()
const localeStore = useLocaleStore()
const { currentProject } = storeToRefs(projectStore)

const project = computed(() => projectStore.currentProject)
const isAr = computed(() => localeStore.isArabic)

const title = computed(() => (isAr.value ? project.value?.title_ar : project.value?.title) || project.value?.title || '')
const category = computed(() => (isAr.value ? project.value?.category_ar : project.value?.category) || '')
const description = computed(() =>
  isAr.value ? project.value?.description_ar || project.value?.description : project.value?.description
)
const gallery = computed(() =>
  Array.isArray(project.value?.gallery) ? project.value.gallery.filter(Boolean) : []
)

/** Category-matched local cover for when the record's own image is missing or dead. */
const cover = computed(() => projectImage(project.value))

// Same idea one level down: every seeded gallery URL points at a domain that
// no longer resolves, so without this the page shows six empty wells.
const galleryFallback = (index) => projectGalleryImage(project.value, index)

const lightbox = reactive({ isOpen: false, index: 0 })
const lightboxClose = ref(null)
let lastFocused = null

const openLightbox = async (index) => {
  lastFocused = document.activeElement
  lightbox.index = index
  lightbox.isOpen = true
  document.body.style.overflow = 'hidden'
  await nextTick()
  lightboxClose.value?.focus()
}

const closeLightbox = () => {
  lightbox.isOpen = false
  document.body.style.overflow = ''
  // Return focus to the thumbnail that opened it, rather than dropping the
  // caret back to the top of the document.
  lastFocused?.focus?.()
}

const step = (delta) => {
  const n = gallery.value.length
  if (!n) return
  lightbox.index = (lightbox.index + delta + n) % n
}
const nextSlide = () => step(1)
const prevSlide = () => step(-1)

/**
 * Swipe. The threshold is 48px and the gesture is rejected if the vertical
 * travel is larger than the horizontal — otherwise a scroll that drifts
 * sideways would flick the frame, which is the classic way a carousel makes a
 * page feel like it is fighting you.
 *
 * Direction follows the language: in Arabic, dragging toward the reading
 * direction moves forward, the same way the arrow keys already do.
 */
const touch = { x: 0, y: 0 }
const SWIPE_MIN = 48

const onTouchStart = (e) => {
  const t = e.changedTouches?.[0]
  if (!t) return
  touch.x = t.clientX
  touch.y = t.clientY
}

const onTouchEnd = (e) => {
  const t = e.changedTouches?.[0]
  if (!t) return
  const dx = t.clientX - touch.x
  const dy = t.clientY - touch.y
  if (Math.abs(dx) < SWIPE_MIN || Math.abs(dx) <= Math.abs(dy)) return
  const forward = isAr.value ? dx > 0 : dx < 0
  forward ? nextSlide() : prevSlide()
}

const handleKeydown = (e) => {
  if (!lightbox.isOpen) return
  if (e.key === 'Escape') closeLightbox()
  else if (e.key === 'ArrowRight') isAr.value ? prevSlide() : nextSlide()
  else if (e.key === 'ArrowLeft') isAr.value ? nextSlide() : prevSlide()
}

useSeo(currentProject)

onMounted(() => {
  projectStore.fetchProject(route.params.id)
  window.addEventListener('keydown', handleKeydown)
})

watch(() => route.params.id, (id) => id && projectStore.fetchProject(id))

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown)
  document.body.style.overflow = ''
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity var(--dur-base) var(--ease-out-quart);
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
