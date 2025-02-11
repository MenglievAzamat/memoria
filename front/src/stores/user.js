import {defineStore} from "pinia";

export const useUserStore = defineStore("user", {
  state: () => {
    return {
      user: null
    }
  }, actions: {
    setUser(user) {
      this.user = user
    },

    // AXIOS
    async fetchUser() {
      await this.$axios.get('/auth/user')
        .then(response => {
          this.setUser(response.data.user)
        })
        .catch(error => {
          if (error.status === 401) {
            localStorage.removeItem(process.env.VUE_APP_TOKEN_KEY);
            location.reload();
          }
        })
    },

    async login(credentials) {
      await this.$axios.post('/auth/login', credentials)
        .then(response => {
          localStorage.setItem(process.env.VUE_APP_TOKEN_KEY, response.data.token);
          location.href = '/';
        })
        .catch(error => {
          if (error.status === 422) {
            alert(error.response.data.message);
          }
        })
    },

    async logout() {
      if (confirm('Вы действительно хотите выйти из аккаунта?')) {
        await this.$axios.delete('/auth/logout')
          .then(response => {
            alert(response.data.message);
            localStorage.removeItem(process.env.VUE_APP_TOKEN_KEY);
            location.reload();
          })
          .catch(error => {});
      }
    }
  }
})