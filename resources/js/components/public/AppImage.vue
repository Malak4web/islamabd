<template>
  <!-- A failed <img> is not a neutral event: the browser renders the alt text
       inside the image box, at body size, on top of whatever is layered over
       it. On the projects grid that meant the alt string collided with the
       "Featured" chip. So a dead source steps down the chain instead — first to
       the locally hosted stand-in, then to a deliberate placeholder mark. -->
  <img
    v-if="active"
    :key="active.src"
    :src="active.src"
    :srcset="active.srcset || undefined"
    :sizes="active.srcset ? sizes : undefined"
    :alt="alt"
    :loading="eager ? 'eager' : 'lazy'"
    :fetchpriority="eager ? 'high' : undefined"
    decoding="async"
    :width="width"
    :height="height"
    :class="imgClass"
    :style="imgStyle"
    @error="step"
  />

  <div
    v-else
    :class="['flex items-center justify-center bg-canvas-inset text-line-strong', imgClass]"
    role="img"
    :aria-label="alt || $t('common.error_image')"
  >
    <EaiIcon :name="placeholder" :size="placeholderSize" :stroke="1" />
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'

const props = defineProps({
  src: { type: String, default: '' },
  srcset: { type: String, default: '' },
  /** Locally hosted stand-in, used when `src` is absent or fails to load. */
  fallbackSrc: { type: String, default: '' },
  fallbackSrcset: { type: String, default: '' },
  sizes: { type: String, default: '100vw' },
  alt: { type: String, default: '' },
  /** Applied to both the image and the placeholder so layout never shifts. */
  imgClass: { type: String, default: '' },
  /** Inline styles for the image only — `object-position` for an off-centre crop. */
  imgStyle: { type: [Object, String], default: undefined },
  eager: { type: Boolean, default: false },
  width: { type: [String, Number], default: undefined },
  height: { type: [String, Number], default: undefined },
  placeholder: { type: String, default: 'image' },
  placeholderSize: { type: [String, Number], default: 44 },
})

const chain = computed(() =>
  [
    { src: props.src, srcset: props.srcset },
    { src: props.fallbackSrc, srcset: props.fallbackSrcset },
  ].filter((entry) => Boolean(entry.src))
)

const index = ref(0)
const active = computed(() => chain.value[index.value] ?? null)

const step = () => {
  index.value += 1
}

// New sources deserve a fresh attempt — otherwise one dead URL poisons the slot
// for every subsequent record rendered through the same recycled node.
watch(
  () => [props.src, props.fallbackSrc],
  () => {
    index.value = 0
  }
)
</script>
