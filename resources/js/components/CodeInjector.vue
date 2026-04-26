<template>
    <!-- CodeInjector: Invisible component handling dynamic snippet injection -->
</template>

<script setup>
import { watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/api/axios'

const route = useRoute()

watch(() => route.name, async (newRouteName) => {
    if (!newRouteName) return

    // Extract base slug. e.g. "project.detail" -> "project" (or handle exact match depending on logic)
    // Actually, following WF: const slug = newRoute.name?.replace('public.','') || 'home'
    const slug = newRouteName.replace('public.', '') || 'home'

    try {
        const { data: responseData } = await api.get(`/v1/code-injections?page=${slug}`)
        const data = responseData.data

        // Inject head snippets
        if (data.head) {
            data.head.forEach(snippet => {
                if (document.querySelector(`[data-snip="${snippet.id}"]`)) return
                const el = document.createElement('div')
                el.innerHTML = snippet.code
                el.setAttribute('data-snip', snippet.id)
                document.head.appendChild(el)
            })
        }

        // Inject body_start snippets
        if (data.body_start) {
            data.body_start.forEach(snippet => {
                if (document.querySelector(`[data-snip-bs="${snippet.id}"]`)) return
                const el = document.createElement('div')
                el.innerHTML = snippet.code
                el.setAttribute('data-snip-bs', snippet.id)
                document.body.insertBefore(el, document.body.firstChild)
            })
        }

        // Inject body_end snippets
        if (data.body_end) {
            data.body_end.forEach(snippet => {
                if (document.querySelector(`[data-snip-be="${snippet.id}"]`)) return
                const el = document.createElement('div')
                el.innerHTML = snippet.code
                el.setAttribute('data-snip-be', snippet.id)
                document.body.appendChild(el)
            })
        }
    } catch (e) {
        // Silently fail if injections can't load, shouldn't break the app
        console.error('Failed to load code injections', e)
    }
}, { immediate: true })
</script>
