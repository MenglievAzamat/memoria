import { defineStore } from "pinia";

export const useLoadingStore = defineStore("loading", {
  state: () => {
    return {
      loading: true
    }
  },
  actions: {
    loadingOn() {
      this.loading = true
    },
    loadingOff() {
      this.loading = false
    },
  }
})