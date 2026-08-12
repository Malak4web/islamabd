<template>
  <div class="border border-line bg-canvas p-5 sm:p-10">
    <!-- Success ---------------------------------------------------------- -->
    <div v-if="success" class="py-10 text-center">
      <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-pill bg-gold-soft text-ink">
        <EaiIcon name="check" :size="30" :stroke="1.5" />
      </div>
      <h3 ref="successHeading" tabindex="-1" class="mt-6 text-heading font-light text-ink">
        {{ $t('contact.success_title') }}
      </h3>
      <p class="mx-auto mt-3 max-w-prose text-ink-muted">{{ $t('contact.success_msg') }}</p>
      <button
        type="button"
        class="press mt-8 rounded-xs bg-gold px-8 py-3.5 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft"
        @click="resetForm"
      >
        {{ $t('contact.send_another') }}
      </button>
    </div>

    <!-- Form ------------------------------------------------------------- -->
    <form v-else class="space-y-6" novalidate @submit.prevent="handleSubmit">
      <div>
        <h3 class="text-heading font-light text-ink">{{ $t('contact.form_heading') }}</h3>
        <!-- The asterisks were previously unexplained. -->
        <p class="mt-2 text-label text-ink-subtle">{{ $t('contact.required_note') }}</p>
      </div>

      <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
          <label :for="ids.name" class="field-label">
            {{ $t('contact.name') }} <span aria-hidden="true" class="text-critical">*</span>
          </label>
          <input
            :id="ids.name"
            v-model="form.name"
            type="text"
            name="name"
            autocomplete="name"
            autocapitalize="words"
            enterkeyhint="next"
            class="field"
            :class="{ 'field--invalid': errors.name }"
            :aria-invalid="errors.name ? 'true' : undefined"
            :aria-describedby="errors.name ? `${ids.name}-err` : undefined"
          />
          <p v-if="errors.name" :id="`${ids.name}-err`" data-error role="alert" class="field-error">
            {{ errors.name }}
          </p>
        </div>

        <div>
          <label :for="ids.phone" class="field-label">
            {{ $t('contact.phone') }} <span aria-hidden="true" class="text-critical">*</span>
          </label>
          <input
            :id="ids.phone"
            v-model="form.phone"
            type="tel"
            name="phone"
            inputmode="tel"
            autocomplete="tel"
            enterkeyhint="next"
            dir="ltr"
            class="field"
            :class="{ 'field--invalid': errors.phone }"
            :aria-invalid="errors.phone ? 'true' : undefined"
            :aria-describedby="errors.phone ? `${ids.phone}-err` : undefined"
            @input="form.phone = form.phone.replace(/[^0-9+ ]/g, '')"
          />
          <p v-if="errors.phone" :id="`${ids.phone}-err`" data-error role="alert" class="field-error">
            {{ errors.phone }}
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
          <label :for="ids.email" class="field-label">{{ $t('contact.email') }}</label>
          <input
            :id="ids.email"
            v-model="form.email"
            type="email"
            name="email"
            autocomplete="email"
            inputmode="email"
            autocapitalize="off"
            autocorrect="off"
            spellcheck="false"
            enterkeyhint="next"
            dir="ltr"
            class="field"
            :class="{ 'field--invalid': errors.email }"
            :aria-invalid="errors.email ? 'true' : undefined"
            :aria-describedby="errors.email ? `${ids.email}-err` : undefined"
          />
          <p v-if="errors.email" :id="`${ids.email}-err`" data-error role="alert" class="field-error">
            {{ errors.email }}
          </p>
        </div>

        <div>
          <label :for="ids.service" class="field-label">{{ $t('contact.service') }}</label>
          <div class="relative">
            <select :id="ids.service" v-model="form.service" name="service" class="field appearance-none pe-11">
              <option value="">{{ $t('contact.select_service') }}</option>
              <option v-for="service in serviceStore.services" :key="service.id" :value="service.title">
                {{ service.title }}
              </option>
            </select>
            <span class="pointer-events-none absolute inset-y-0 end-4 flex items-center text-ink-subtle">
              <EaiIcon name="chevron-down" :size="16" />
            </span>
          </div>
        </div>
      </div>

      <div>
        <label :for="ids.message" class="field-label">
          {{ $t('contact.message') }} <span aria-hidden="true" class="text-critical">*</span>
        </label>
        <textarea
          :id="ids.message"
          v-model="form.message"
          name="message"
          rows="5"
          autocapitalize="sentences"
          enterkeyhint="enter"
          class="field resize-y"
          :class="{ 'field--invalid': errors.message }"
          :aria-invalid="errors.message ? 'true' : undefined"
          :aria-describedby="errors.message ? `${ids.message}-err` : undefined"
        ></textarea>
        <p v-if="errors.message" :id="`${ids.message}-err`" data-error role="alert" class="field-error">
          {{ errors.message }}
        </p>
      </div>

      <button
        type="submit"
        :disabled="store.isLoading"
        class="press flex w-full items-center justify-center gap-3 rounded-xs bg-gold px-8 py-4 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft disabled:cursor-not-allowed disabled:opacity-60"
      >
        <span
          v-if="store.isLoading"
          class="h-4 w-4 animate-spin rounded-pill border-2 border-ink/25 border-t-ink"
          aria-hidden="true"
        ></span>
        {{ store.isLoading ? $t('contact.sending') : $t('contact.submit') }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, nextTick, useId } from 'vue'
import { useI18n } from 'vue-i18n'
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, email } from '@vuelidate/validators'
import { useContactStore } from '@/stores/contactStore'
import { useServiceStore } from '@/stores/serviceStore'
import EaiIcon from '@/components/icons/EaiIcon.vue'

const { t } = useI18n()
const store = useContactStore()
const serviceStore = useServiceStore()

const success = ref(false)
const successHeading = ref(null)

// Stable, unique ids so <label for> actually points at its input. Without the
// association a screen reader announces these fields as unlabelled, and tapping
// a label does not focus its control.
const uid = typeof useId === 'function' ? useId() : `cf-${Math.random().toString(36).slice(2, 8)}`
const ids = {
  name: `${uid}-name`,
  phone: `${uid}-phone`,
  email: `${uid}-email`,
  service: `${uid}-service`,
  message: `${uid}-message`,
}

const form = reactive({ name: '', phone: '', email: '', service: '', message: '' })

const numeric = (value) => !value || /^[0-9+ ]+$/.test(value)

const rules = {
  name: { required },
  phone: { required, numeric },
  email: { email },
  message: { required, minLength: minLength(10) },
}

const v$ = useVuelidate(rules, form)

/**
 * One message per field, whichever applies first: client-side validation, then
 * whatever the server rejected. Previously both could render at once, stacking
 * two contradictory errors under the same input.
 */
const errors = computed(() => {
  const out = {}
  const serverFirst = (key) => {
    const fromServer = store.formErrors?.[key]
    return Array.isArray(fromServer) ? fromServer[0] : fromServer
  }

  if (v$.value.name.$error) out.name = t('contact.req_name')
  else if (serverFirst('name')) out.name = serverFirst('name')

  if (v$.value.phone.$error) out.phone = t('contact.req_phone')
  else if (serverFirst('phone')) out.phone = serverFirst('phone')

  if (v$.value.email.$error) out.email = t('contact.req_email')
  else if (serverFirst('email')) out.email = serverFirst('email')

  if (v$.value.message.$error) out.message = t('contact.req_msg')
  else if (serverFirst('message')) out.message = serverFirst('message')

  return out
})

const handleSubmit = async () => {
  const valid = await v$.value.$validate()
  if (!valid) return

  try {
    await store.submitContact(form)
    success.value = true
    // Move focus to the confirmation: the form the user was in has just been
    // replaced, so without this the focus ring is left on a detached node.
    await nextTick()
    successHeading.value?.focus()
  } catch {
    // Field-level errors surface through the store.
  }
}

const resetForm = () => {
  success.value = false
  Object.assign(form, { name: '', phone: '', email: '', service: '', message: '' })
  v$.value.$reset()
}

onMounted(() => {
  if (!serviceStore.services.length) serviceStore.fetchServices()
})
</script>

<style scoped>
.field-label {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.8125rem;
  font-weight: 600;
  color: oklch(var(--c-ink));
}

.field {
  width: 100%;
  /* 16px is not a taste decision here: iOS Safari zooms the viewport on focus
     for any field under it, and once zoomed the page stays zoomed — the
     visitor finishes the form fighting a horizontally scrolling layout. */
  font-size: 1rem;
  min-height: 3rem;
  padding: 0.875rem 1rem;
  border: 1px solid oklch(var(--c-line-strong));
  border-radius: 4px;
  background-color: oklch(var(--c-surface));
  color: oklch(var(--c-ink));
  transition: border-color var(--dur-fast) var(--ease-out-quart);
}
.field::placeholder {
  /* Placeholder text is text: it needs the same 4.5:1 as body copy, not the
     browser default light grey. */
  color: oklch(var(--c-ink-subtle));
}
.field:focus {
  outline: none;
  border-color: oklch(var(--c-ink));
  box-shadow: 0 0 0 3px oklch(var(--c-ink) / 0.1);
}
.field--invalid {
  border-color: oklch(var(--c-critical));
}
.field--invalid:focus {
  box-shadow: 0 0 0 3px oklch(var(--c-critical) / 0.14);
}

.field-error {
  margin-top: 0.5rem;
  font-size: 0.8125rem;
  /* Tailwind's red-500 measures ~3.4:1 on this canvas — under the 4.5 floor for
     the one piece of text a blocked user has to read. */
  color: oklch(var(--c-critical));
}
</style>
