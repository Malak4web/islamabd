import { mount } from '@vue/test-utils'
import { describe, it, expect, beforeEach } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import BottomNav from '@/components/public/BottomNav.vue'
import i18n from '@/i18n'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: { template: '<div />' } },
    { path: '/about', component: { template: '<div />' } },
    { path: '/services', component: { template: '<div />' } },
    { path: '/services/:id', component: { template: '<div />' } },
    { path: '/projects', component: { template: '<div />' } },
    { path: '/contact', component: { template: '<div />' } },
  ],
})

const mountNav = async (path = '/') => {
  await router.push(path)
  await router.isReady()
  return mount(BottomNav, { global: { plugins: [router, i18n] } })
}

describe('BottomNav.vue', () => {
  beforeEach(() => setActivePinia(createPinia()))

  it('offers exactly five destinations, each with a label and a mark', async () => {
    const wrapper = await mountNav('/')
    const tabs = wrapper.findAll('a')

    // Five is the ceiling for a tab bar and the whole site map — if a sixth
    // route ever appears this test is the thing that says so.
    expect(tabs).toHaveLength(5)

    for (const tab of tabs) {
      expect(tab.find('svg').exists()).toBe(true)
      expect(tab.find('.tab__label').text().length).toBeGreaterThan(0)
    }
  })

  it('marks the current destination with more than colour', async () => {
    const wrapper = await mountNav('/projects')
    const current = wrapper.findAll('a').find((a) => a.attributes('href') === '/projects')

    expect(current.classes()).toContain('tab--on')
    // The gold rule above the tab is the non-colour cue; it has to be in the
    // DOM for the active state to be legible to anyone who cannot separate
    // ink from ink-subtle.
    expect(current.find('.tab__rule').exists()).toBe(true)
  })

  it('keeps the section tab current on a detail route', async () => {
    const wrapper = await mountNav('/services/7')
    const services = wrapper.findAll('a').find((a) => a.attributes('href') === '/services')

    expect(services.classes()).toContain('tab--on')
  })

  it('does not mark home as current on every route', async () => {
    // `/` is a prefix of every path, so the default prefix-matching active
    // class would light the home tab everywhere.
    const wrapper = await mountNav('/about')
    const home = wrapper.findAll('a').find((a) => a.attributes('href') === '/')

    expect(home.classes()).not.toContain('tab--on')
  })

  it('names itself for assistive tech', async () => {
    const wrapper = await mountNav('/')
    expect(wrapper.find('nav').attributes('aria-label')).toBeTruthy()
  })
})
