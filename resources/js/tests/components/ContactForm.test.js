import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import ContactForm from '@/components/public/ContactForm.vue'
import { useContactStore } from '@/stores/contactStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('ContactForm.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders all required fields', () => {
        const wrapper = mount(ContactForm)
        
        expect(wrapper.find('input[type="text"]').exists()).toBe(true) // name
        expect(wrapper.find('input[type="tel"]').exists()).toBe(true) // phone
        expect(wrapper.find('input[type="email"]').exists()).toBe(true) // email
        expect(wrapper.find('select').exists()).toBe(true) // service
        expect(wrapper.find('textarea').exists()).toBe(true) // message
    })

    it('shows error when name empty on submit', async () => {
        const wrapper = mount(ContactForm)
        
        await wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        
        expect(wrapper.text()).toContain('Name is required')
    })

    it('shows error when message too short', async () => {
        const wrapper = mount(ContactForm)
        
        const inputs = wrapper.findAll('input')
        await inputs[0].setValue('Sara') // name
        await inputs[1].setValue('123456789') // phone
        await wrapper.find('textarea').setValue('Hi') // short message
        
        await wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        
        expect(wrapper.text()).toContain('Message must be at least 10 characters')
    })

    it('shows success message after submit', async () => {
        const store = useContactStore()
        vi.spyOn(store, 'submitContact').mockResolvedValue(true)
        
        const wrapper = mount(ContactForm)
        
        const inputs = wrapper.findAll('input')
        await inputs[0].setValue('Sara')
        await inputs[1].setValue('123456789')
        await wrapper.find('textarea').setValue('This is a long message')
        
        await wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        
        expect(wrapper.text()).toContain('Thank You!')
    })

    it('form resets after successful submit', async () => {
        const store = useContactStore()
        vi.spyOn(store, 'submitContact').mockResolvedValue(true)
        
        const wrapper = mount(ContactForm)
        
        const inputs = wrapper.findAll('input')
        await inputs[0].setValue('Sara')
        await inputs[1].setValue('123456789')
        await wrapper.find('textarea').setValue('This is a long message')
        
        await wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        
        const resetBtn = wrapper.find('button')
        await resetBtn.trigger('click')
        await flushPromises()
        
        expect(wrapper.find('input[type="text"]').element.value).toBe('')
        expect(wrapper.find('textarea').element.value).toBe('')
    })
})
