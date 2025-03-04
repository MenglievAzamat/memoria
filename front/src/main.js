import {createApp} from 'vue'
import { createPinia } from "pinia"
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import axios from './plugins/axios'

import './assets/css/main.css'

const app = createApp(App)

const pinia = createPinia()

pinia.use(({ store }) => {
  store.$axios = app.config.globalProperties.$axios;
  store.$router = app.config.globalProperties.$router;
});

app.use(router)
  .use(axios, {
    baseUrl: 'https://api.memoriabook.online/public/api/',
    // baseUrl: 'http://memoria.test/api',
  })
  .use(pinia)
  .mount('#app')

