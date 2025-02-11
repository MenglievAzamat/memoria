<template>
    <div class="overflow-scroll w-full h-full bg-[#131313]">
        <div class="logo flex flex-col justify-center items-center px-[2.25rem] py-[4.6875rem]">
            <img class="w-[15.5rem] h-[3.875rem] mb-[1.875rem]" src="@/assets/img/logo.png" alt="logo.png">

            <Progress :progress="percentage"/>

            <p class="text-[0.875rem] mt-2">{{ `${current_page} из 100 страниц` }}</p>
        </div>
        <div class="menu h-[24.75rem] overflow-scroll mb-4">
            <MenuItem text="Обложка" to="/client/book/cover"/>
            <MenuItem text="Титульный лист" to="/client/book/title"/>
            <MenuItem text="Содержание:" disabled/>
            <MenuItem v-for="(chapter, index) in chapters" :key="chapter.id" :text="(index + 1) + '. ' + cropText(chapter.title)" :to="`/client/book/chapters/${chapter.id}`"/>
        </div>
        <div class="final w-full">
            <button class="w-full rounded-none mb-1" @click="toggleBook" v-if="isAdmin && store.book?.open">Закрыть книгу</button>
            <button class="w-full rounded-none mb-1" @click="toggleBook" v-else-if="isAdmin && !store.book?.open">Открыть книгу</button>

            <button class="w-full rounded-none mb-1" @click="popupActive = true" v-if="isAdmin">Добавить главу</button>
            <button class="w-full rounded-none mb-[3.5rem]" @click="review">Просмотр книги</button>

            <div class="px-[2.25rem] mb-[0.625rem]"><a class="text-[1.3125rem] text-[#FDD892] opacity-[64%]" href="/">Выйти на главную страницу</a></div>
        </div>

        <div class="absolute top-0 bottom-0 right-0 left-0 flex justify-center items-center" v-if="popupActive">
            <div class="background absolute w-full h-full bg-black/80 z-20"></div>
            <div class="body z-30 rounded min-w-[50rem] p-4">
                <textarea v-model="question" placeholder="Вопрос..."/>
                <textarea v-model="title" placeholder="Оглавление..."/>
                <div class="flex justify-between">
                    <button @click="savePage">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Progress from "@/components/Book/Progress.vue";
import MenuItem from "@/components/Book/MenuItem.vue";
import {useBookStore} from "@/stores/book";
import {useUserStore} from "@/stores/user";
import {useAdminStore} from "@/stores/admin";

export default {
    name: "Sidebar",

    components: {MenuItem, Progress},

    data: () => ({
        store: useBookStore(),
        adminStore: useAdminStore(),
        userStore: useUserStore(),

        title: null,
        question: null,

        current_page: 1,
        total_pages: 100,
        chapters: [],

        popupActive: false,
    }),

    computed: {
        percentage() {
            return this.total_pages ? Math.round(this.current_page * 100 / 100) : 0;
        },

        isAdmin() {
            return this.userStore.user.type === 'admin'
        }
    },

    methods: {
        cropText(text) {
            let length = 20
            let result = text.slice(0, length)

            return  result.length < length ? result : (result + '...');
        },

        async savePage() {
            await this.adminStore.saveChapter(this.store.book.id, {
                title: this.title,
                question: this.question
            })
        },

        review() {
            this.$router.push('/client/book/review')
        },

        async toggleBook() {
            await this.store.toggleBook(this.store.book.id).then(() => {
                location.reload()
            })
        }
    },

    mounted() {
        this.total_pages = this.store.chapters.total_count
        this.current_page = this.store.chapters.progress
        this.chapters = this.store.chapters.chapters

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.popupActive = false
            }
        })
    }
}
</script>