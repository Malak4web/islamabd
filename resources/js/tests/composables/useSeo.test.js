import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { ref, nextTick } from 'vue'
import { useSeo } from '@/composables/useSeo'

describe('useSeo composable', () => {
    beforeEach(() => {
        document.head.innerHTML = ''
        document.title = ''
    })

    afterEach(() => {
        document.head.innerHTML = ''
    })

    it('sets document title from meta_title', async () => {
        const page = ref({
            meta_title: 'Custom Title',
            title: 'Page Title'
        })
        
        useSeo(page)
        await nextTick()
        
        expect(document.title).toBe('Custom Title')
    })

    it('falls back to title if meta_title is missing', async () => {
        const page = ref({
            title: 'Page Title'
        })
        
        useSeo(page)
        await nextTick()
        
        expect(document.title).toBe('Page Title')
    })

    it('sets og:image meta tag', async () => {
        const page = ref({
            title: 'Test',
            og_image: 'https://example.com/image.jpg'
        })
        
        useSeo(page)
        await nextTick()
        
        const meta = document.querySelector('meta[property="og:image"]')
        expect(meta).not.toBeNull()
        expect(meta.getAttribute('content')).toBe('https://example.com/image.jpg')
    })

    it('updates title when page reactive data changes', async () => {
        const page = ref({
            title: 'Old Title'
        })
        
        useSeo(page)
        await nextTick()
        expect(document.title).toBe('Old Title')
        
        page.value.title = 'New Title'
        await nextTick()
        expect(document.title).toBe('New Title')
    })
})
