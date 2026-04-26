import { watchEffect } from 'vue'
import { useSettingStore } from '@/stores/settingStore'

export function useSeo(page) {
    const settingStore = useSettingStore()

    watchEffect(() => {
        if (!page.value) return

        // Set Title
        const title = page.value.meta_title || page.value.title || 'InDesign'
        document.title = title

        // Set Meta Tags
        setMeta('description', page.value.meta_description)
        setMeta('og:title', page.value.meta_title || title)
        setMeta('og:description', page.value.meta_description)
        setMeta('og:image', page.value.og_image)
        setMeta('og:type', 'website')
        setMeta('twitter:card', 'summary_large_image')

        // Set Favicon
        const faviconUrl = settingStore.settings.favicon
        if (faviconUrl) {
            let link = document.querySelector("link[rel*='icon']") || document.createElement('link')
            link.type = 'image/x-icon'
            link.rel = 'shortcut icon'
            link.href = faviconUrl
            document.getElementsByTagName('head')[0].appendChild(link)
        }
    })
}

function setMeta(name, content) {
    if (content === undefined || content === null) content = ''
    
    let el = document.querySelector(`meta[name="${name}"], meta[property="${name}"]`)
    
    if (!el) {
        el = document.createElement('meta')
        el.setAttribute(name.startsWith('og:') || name.startsWith('twitter:') ? 'property' : 'name', name)
        document.head.appendChild(el)
    }
    
    el.setAttribute('content', content)
}
