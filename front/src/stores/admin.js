import {defineStore} from "pinia";

export const useAdminStore = defineStore("admin", {
  state: () => {
    return {
      books: false
    }
  },

  actions:
    {
      setBooks(books) {
        this.books = books
      }
      ,

      async fetchBooks() {
        await this.$axios.get('/admin/books').then(response => {
          this.setBooks(response.data.books)
        })
      }
      ,

      async saveUser(payload) {
        await this.$axios.post('/admin/user', payload).then(response => {
          alert(response.data.message)
          location.reload()
        })
      },

      async saveChapter(bookId, payload) {
        await this.$axios.post(`/admin/book/${bookId}/chapter`, payload)
          .then(response => {
            alert(response.data.message)
            location.reload()
          })
      },

      async saveChapterQuestion(chapterId, payload) {
        await this.$axios.put(`/admin/chapter/${chapterId}/question`, payload)
          .then(response => {
            alert(response.data.message)
            location.reload()
          })
      }
    }
})