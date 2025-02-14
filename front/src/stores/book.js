import {defineStore} from "pinia";

export const useBookStore = defineStore("book", {
  state: () => {
    return {
      book: null,
      chapter: null,
      chapters: [],
      covers: [],
    }
  },
  actions: {
    setBook(book) {
      this.book = book
    },

    setChapter(chapter) {
      this.chapter = chapter
    },

    setChapters(chapters) {
      this.chapters = chapters
    },

    setCovers(covers) {
      this.covers = covers
    },

    async fetchBookById(bookId) {
      await this.$axios.get(`/book/${bookId}`)
        .then(response => {
          this.setBook(response.data.book)
        })
    },

    async fetchBookByUserId(userId) {
      await this.$axios.get(`/book/user/${userId}`)
        .then(response => {
          this.setBook(response.data.book)
        })
    },

    async upsertQuestionnaire(bookId, payload) {
      await this.$axios.post(`/book/${bookId}/questionnaire`, payload)
        .then(response => {
          alert('Ваша анкета успешно сохранена')
          let book = response.data.book
          this.setBook(book)
          this.$router.push('/')
        })
    },

    async getCovers() {
      await this.$axios.get('/book/covers')
        .then(response => {
          this.setCovers(response.data.covers)
        })
    },

    async saveCover(bookId, coverId) {
      await this.$axios.post(`/book/${bookId}/cover`, {cover_type_id: coverId})
        .then(response => {
          this.setBook(response.data.book)
        })
    },

    async getChapters(bookId) {
      await this.$axios.get(`/book/${bookId}/chapters`)
        .then(response => {
          this.setChapters(response.data)
        })
    },

    async saveTitle(bookId, payload) {
      await this.$axios.post(`/book/${bookId}/title`, payload)
        .then(response => {
          alert(response.data.message)
          this.setBook(response.data.book)
        })
    },

    async getChapter(chapterId) {
      return await this.$axios.get(`/chapter/${chapterId}`).then(response => {
        this.setChapter(response.data.chapter)
        return response.data
      })
    },

    async saveChapter(chapterId, text) {
      await this.$axios.post(`/chapter/${chapterId}`, { text: text }).then(response => {
        alert(response.data.message)
      })
    },

    async generatePDF(bookId) {
      await this.$axios.get(`/book/${bookId}/pdf`, {
        responseType: 'blob'
      }).then(response => {
        // create file link in browser's memory
        const href = URL.createObjectURL(response.data);

        // create "a" HTML element with href to file & click
        const link = document.createElement('a');
        link.href = href;
        link.setAttribute('download', 'file.pdf'); //or any other extension
        document.body.appendChild(link);
        link.click();

        // clean up "a" element & remove ObjectURL
        document.body.removeChild(link);
        URL.revokeObjectURL(href);
      })
    },

    async closeBook(bookId) {
      return await this.$axios.post(`/book/${bookId}/close`).then(response => {
        return response.data.message
      })
    },

    async toggleBook(bookId) {
      await this.$axios.post(`/book/${bookId}/toggle`).then(response => {
        alert(response.data.message)
      })
    }
  }
})