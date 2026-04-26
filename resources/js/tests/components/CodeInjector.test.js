import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest'
import { createRouter, createWebHistory } from 'vue-router'
import CodeInjector from '@/components/CodeInjector.vue'
import api from '@/api/axios'

vi.mock('@/api/axios')

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'public.home', component: { template: '<div></div>' } },
        { path: '/about', name: 'public.about', component: { template: '<div></div>' } }
    ]
})

describe('CodeInjector.vue', () => {
    beforeEach(async () => {
        vi.clearAllMocks()
        document.head.innerHTML = ''
        document.body.innerHTML = ''
        router.push('/')
        await router.isReady()
    })

    afterEach(() => {
        document.head.innerHTML = ''
        document.body.innerHTML = ''
    })

    it('fetches injections on mount for current route', async () => {
        api.get.mockResolvedValueOnce({ data: { data: { head: [], body_start: [], body_end: [] } } })
        
        mount(CodeInjector, {
            global: { plugins: [router] }
        })
        await flushPromises()
        
        expect(api.get).toHaveBeenCalledWith('/v1/code-injections?page=home')
    })

    it('injects head snippet into document head', async () => {
        api.get.mockResolvedValueOnce({ 
            data: { 
                data: { 
                    head: [{ id: 1, code: '<meta name="test" content="123">' }], 
                    body_start: [], 
                    body_end: [] 
                } 
            } 
        })
        
        mount(CodeInjector, {
            global: { plugins: [router] }
        })
        await flushPromises()
        
        expect(document.head.innerHTML).toContain('meta name="test"')
        expect(document.head.innerHTML).toContain('data-snip="1"')
    })

    it('does not duplicate already injected snippet', async () => {
        api.get.mockResolvedValueOnce({ 
            data: { 
                data: { 
                    head: [{ id: 1, code: '<meta name="test">' }], 
                    body_start: [], 
                    body_end: [] 
                } 
            } 
        })
        
        // Manually inject a snippet with the same ID
        const el = document.createElement('div')
        el.setAttribute('data-snip', '1')
        document.head.appendChild(el)
        
        mount(CodeInjector, {
            global: { plugins: [router] }
        })
        await flushPromises()
        
        // Should only have the one we injected manually
        const snippets = document.head.querySelectorAll('[data-snip="1"]')
        expect(snippets.length).toBe(1)
        expect(document.head.innerHTML).not.toContain('<meta name="test">')
    })

    it('fetches new injections on route change', async () => {
        api.get.mockResolvedValue({ data: { data: { head: [], body_start: [], body_end: [] } } })
        
        mount(CodeInjector, {
            global: { plugins: [router] }
        })
        await flushPromises()
        
        expect(api.get).toHaveBeenCalledWith('/v1/code-injections?page=home')
        
        await router.push('/about')
        await flushPromises()
        
        expect(api.get).toHaveBeenCalledWith('/v1/code-injections?page=about')
    })
})
