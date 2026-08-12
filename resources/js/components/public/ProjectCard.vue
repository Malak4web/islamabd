<template>
  <RouterLink
    :to="{ name: 'project.detail', params: { id: project.id } }"
    class="press group block focus-visible:outline-offset-4"
  >
    <!-- The photograph rides forward off the caption on hover and drifts against
         the scroll inside its own frame, so the arch behaves like an opening
         with a room behind it rather than a picture lying on the page. On a
         phone the tilt never engages and the drift is the whole effect — which
         is the right split, since a finger is already covering the card it
         would have been rotating. -->
    <figure v-depth v-tilt class="set-plate">
      <div
        v-reveal:wipe
        class="arch tilt-lift relative overflow-hidden bg-canvas-inset [--arch-rise:22%]"
      >
        <AppImage
          :src="project.cover_image"
          :fallback-src="cover.src"
          :fallback-srcset="cover.srcset"
          sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
          :alt="project.title"
          :placeholder-size="56"
          img-class="plate-img aspect-[4/5] w-full object-cover"
        />
        <span class="sheen" aria-hidden="true"></span>
      </div>
    </figure>

    <!-- The caption sits below the photograph rather than floating on it.
         Text over an arbitrary image can never guarantee contrast, and the old
         overlay also hid the "view project" affordance until hover — which on
         a touch device means it never appeared at all. -->
    <div class="flex items-baseline justify-between gap-4 border-t border-line pt-4">
      <div class="min-w-0">
        <h3 class="truncate text-subhead font-medium text-ink transition-colors duration-base group-hover:text-gold-deep">
          {{ project.title }}
        </h3>
        <p class="mt-1 flex items-center gap-2 text-label text-ink-subtle">
          <!-- Featured used to be a chip pinned to the image's top corner. The
               arch cuts those corners away, and a chip on a photograph could
               never guarantee its own contrast anyway; it is a marker in the
               caption now. -->
          <span
            v-if="project.is_featured"
            class="block h-1.5 w-1.5 flex-none bg-gold"
            :title="$t('projects.featured')"
            aria-hidden="true"
          ></span>
          <span v-if="project.is_featured" class="sr-only">{{ $t('projects.featured') }}</span>
          <span v-if="project.category" class="truncate capitalize">{{ project.category }}</span>
        </p>
      </div>

      <EaiIcon
        name="arrow-right"
        :size="18"
        flip
        class="mt-1 flex-none text-ink-subtle transition-all duration-base ease-out-quart group-hover:translate-x-1 group-hover:text-gold-deep rtl:group-hover:-translate-x-1"
      />
    </div>
  </RouterLink>
</template>

<script setup>
import { computed } from 'vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import AppImage from './AppImage.vue'
import { projectImage } from '@/lib/media'

const props = defineProps({
  project: { type: Object, required: true },
})

/**
 * Category-matched local cover, used when the record has no image or its remote
 * one fails. Every seeded cover currently points at a WordPress domain that no
 * longer resolves, so in practice this is what the grid shows today.
 */
const cover = computed(() => projectImage(props.project))
</script>
