import axios from 'axios';
import { App } from 'vue';

export default {
  install: (app, options) => {
    let token = localStorage.getItem(process.env.VUE_APP_TOKEN_KEY)

    app.config.globalProperties.$axios = axios.create({
      baseURL: options.baseUrl,
      headers: {
        Authorization : token ? `Bearer ${token}` : '',
      }
    })
  }
}