<template>
    <div v-if="pages.length > 0">
        <Preview :payload="pages[page-1]"/>
        <div class="flex justify-center items-center mb-8">
            <Pagination :class="{'ml-[10.5625rem]' : userStore.user.type === 'admin' }" :page="page"
                        :total_pages="pages.length" @nextPage="nextPage" @prevPage="prevPage"/>
            <button class="ml-4" v-if="userStore.user.type === 'admin'" @click="generatePDF">PDF</button>
            <button class="ml-4" @click="closeBook" v-if="userStore.user.type === 'client'">Завершить книгу</button>
        </div>
    </div>
</template>

<script>
import Preview from "@/components/Book/Preview.vue";
import Pagination from "@/components/Pagination.vue";
import {useBookStore} from "@/stores/book";
import {useUserStore} from "@/stores/user";
import {chapterDivider, pageFormatter} from "@/plugins/helpers";

export default {
    name: "ReviewView",

    components: {Pagination, Preview},

    data: () => ({
        bookStore: useBookStore(),
        userStore: useUserStore(),

        page: 1,
        pages: [],
    }),

    methods: {
        getPages() {
            let book = this.bookStore.book
            let chapters = this.bookStore.chapters.chapters
            let pages = []

            this.pages[0] = {
                title: book.title,
                subtitle: book.subtitle,
            }

            for (let chapter of chapters) {
                let chapterPages = chapterDivider(chapter.text)

                pages.push(...pageFormatter(chapterPages, chapter.title, chapter.last_page ?? 0))
            }

            while (pages.length > 0) {
                this.pages.push(pages.splice(0, 2))
            }
        },

        nextPage() {
            if (this.page < this.pages.length) {
                this.page++
            }
        },

        prevPage() {
            if (this.page > 1) {
                this.page--
            }
        },

        generatePDF() {
            this.$router.push('/client/book/pdf')
        },

        async closeBook() {
            if (confirm('Вы действительно хотите завершить книгу? Вы не сможете редактировать свои внесённые изменения!')) {
                await this.bookStore.closeBook(this.bookStore.book.id).then(message => {
                    alert(message)
                    location.href = '/'
                })
            }
        }
    }
    ,

    mounted() {
        this.bookStore.getChapters(this.bookStore.book.id).then(() => {
            this.getPages()
        })
    }
}
</script>