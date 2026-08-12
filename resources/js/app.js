import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index.js'
import App from './App.vue'
import i18n from './i18n/index.js'
import reveal from './directives/reveal.js'
import depth from './directives/depth.js'
import tilt from './directives/tilt.js'
import '../css/app.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(i18n)
app.directive('reveal', reveal)
app.directive('depth', depth)
app.directive('tilt', tilt)

// Initialize locale from local storage on app startup
import { useLocaleStore } from './stores/localeStore'
const localeStore = useLocaleStore(pinia)
localeStore.initLocale()

app.mount('#app')
