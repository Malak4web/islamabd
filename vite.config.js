import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel({ input:['resources/css/app.css','resources/js/app.js'], refresh:true }),
    vue({ template:{ transformAssetUrls:{ base:null, includeAbsolute:false } } }),
  ],
  resolve: { alias: { '@': path.resolve(__dirname,'resources/js') } },
  server: {
    proxy: {
      '/api': 'http://127.0.0.1:8000',
      '/sanctum': 'http://127.0.0.1:8000',
      '/storage': 'http://127.0.0.1:8000',
    }
  },
  test: { 
    environment:'jsdom', 
    globals:true, 
    setupFiles:'resources/js/tests/setup.js',
    poolOptions: {
      forks: {
        singleFork: true
      }
    }
  },
})
