import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index.js'
import App from './App.vue'
import i18n from './i18n/index.js'
import '../css/app.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(i18n)

// Initialize locale from local storage on app startup
import { useLocaleStore } from './stores/localeStore'
const localeStore = useLocaleStore(pinia)
localeStore.initLocale()

app.mount('#app')
