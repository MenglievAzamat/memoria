<template>
    <Loading v-if="loading"/>
    <div v-else>
        <p v-if="user.type !== 'admin'" class="leading-none text-[1.5rem] mb-10">{{ chapter?.question }}</p>
        <div v-else>
            <div class="flex">
                <input class="text-[1.5rem] mb-4 h-[2.9375rem] !rounded-r-none" type="text" v-model="chapter.title" placeholder="Заголовок...">
            </div>
            <div class="flex">
                <input class="text-[1.5rem] mb-10 h-[2.9375rem] !rounded-r-none" type="text" v-model="chapter.question" placeholder="Вопрос...">
                <button class="!rounded-l-none" @click="saveQuestion">Сохранить</button>
            </div>
        </div>

        <p class="mb-4">Сраница: {{ page.page_number }}</p>
        <textarea
            class="mb-4"
            rows="8"
            placeholder="Введите текст..."
            v-model="page.text"
            :disabled="!!page.image"
        ></textarea>
        <div class="flex justify-between mb-8">
            <div class="flex items-start">
                <button class="mr-2" @click="savePage">Сохранить</button>
                <button class="mr-2" @click="startUpload">
                    <Icon class="mr-1" type="folder" color="black"/>
                    Добавить фото
                </button>
                <button @click="addPage" v-if="(page.text || page.image) && currentPage + 1 === totalPages">
                    + Новая страница
                </button>
                <input id="fileInput" type="file" hidden @input="makeInput">
            </div>

            <div>
                <p>{{ length }} / 500</p>
            </div>
        </div>

        <Pagination
            :page="currentPage + firstPage"
            :total_pages="totalPages + firstPage - 1"
            @nextPage="nextPage"
            @prevPage="previousPage"
            class="mb-[2.75rem]"
        />

        <Preview :payload="previewPayload"/>
    </div>
</template>

<script>
import Icon from "@/components/Icon.vue";
import Preview from "@/components/Book/Preview.vue";
import {useBookStore} from "@/stores/book";
import Loading from "@/components/Loading.vue";
import Pagination from "@/components/Pagination.vue";
import {useUserStore} from "@/stores/user";
import {useAdminStore} from "@/stores/admin";

export default {
    name: "ChapterView",
    components: {Pagination, Loading, Preview, Icon},

    data: () => ({
        bookStore: useBookStore(),
        userStore: useUserStore(),
        adminStore: useAdminStore(),

        chapter: {},
        pages: [],

        prevPage: null,

        page: {
            id: 0,
            text: null,
            image: null,
            question: null,
        },

        pageText: null,

        currentPage: 0,
        totalPages: 0,
        firstPage: 1,

        image: null,

        loading: false,
    }),

    computed: {
        user() {
            return this.userStore.user
        },

        length() {
            return this.page.text ? this.page.text.length : 0
        },

        previewPayload() {
            let payload = [];

            if (this.prevPage) {
                payload.push({
                    text: this.prevPage.text,
                    image: this.prevPage.image,
                    page: this.prevPage.page_number,
                    title: this.prevPage.page_number === this.firstPage ? this.chapter.title : null
                })
            } else  {
                if (this.page.page_number % 2 === 0) {
                    payload.push({
                        text: null,
                        image: null,
                        page: null,
                    })
                }
            }

            payload.push({
                text: this.page.text,
                image: this.page.image,
                page: this.page.page_number,
                title: this.page.page_number === this.firstPage ? this.chapter.title : null
            })

            if (this.pages[this.currentPage + 1]) {
                payload.push({
                    text: this.pages[this.currentPage + 1].text,
                    image: this.pages[this.currentPage + 1].image,
                    page: this.pages[this.currentPage + 1].page_number,
                })
            }

            return payload
        }
    },

    methods: {
        startUpload() {
            document.querySelector('#fileInput').click()
        },

        makeInput() {
            const input = document.querySelector('#fileInput')
            const files = input.files

            if (files && files[0]) {
                const reader = new FileReader()

                reader.onload = (e) => {
                    this.page.image = e.target.result
                }

                reader.readAsDataURL(files[0])
                this.image = files[0]
                this.$emit('input', files[0])
            }
        },

        async savePage() {
            this.loading = true

            let data = new FormData;
            data.append('text', this.page.text)
            data.append('image', this.image)

            await this.bookStore.savePage(this.page.id, data)
                .then(() => {
                    this.page = this.bookStore.page
                })
                .finally(() => {
                    location.reload()
                })
        },

        async addPage() {
            if (!this.canUpdate()) return;

            this.loading = true

            await this.bookStore.addPage(this.chapter.id)
                .then(async () => {
                    if (this.page.page_number % 2 !== 0) {
                        this.setPrevPage()
                    } else {
                        this.prevPage = null
                    }

                    await this.getChapter().then(() => {
                        this.page = this.bookStore.page
                        this.currentPage = this.pages.length - 1
                    })
                })
                .catch(err => {
                    alert(err.response.data.message)
                })
                .finally(() => {
                    this.loading = false
                })
        },

        async getChapter() {
            this.loading = true
            this.prevPage = null
            this.currentPage = 0

            await this.bookStore.getChapter(this.$route.params.id)
                .then(() => {
                    this.chapter = this.bookStore.chapter
                    this.pages = this.chapter.pages
                    this.page = this.pages[0]
                    this.firstPage = this.page.page_number
                    this.totalPages = this.pages.length
                })
                .finally(() => {
                    this.loading = false
                })
        },

        async saveQuestion() {
            let chapterId = this.chapter.id
            let payload = {
                question: this.chapter.question,
                title: this.chapter.title
            }

            this.loading = true

            await this.adminStore.saveChapterQuestion(chapterId, payload)
        },

        setPrevPage() {
            this.prevPage = this.page
        },

        nextPage() {
            if (!this.canUpdate()) return;

            if (this.currentPage + 1 !== this.totalPages) {
                if (this.page.page_number % 2 !== 0) {
                    this.prevPage = this.page
                } else {
                    this.prevPage = null
                }

                this.page = this.pages[++this.currentPage]
            }
        },

        previousPage() {
            if (!this.canUpdate()) return;

            if (this.currentPage + 1 !== 1) {
                if (this.page.page_number % 2 !== 0) {
                    this.prevPage = this.pages[this.currentPage - 2]
                } else {
                    this.prevPage = null
                }

                this.page = this.pages[--this.currentPage]
            }
        },

        canUpdate() {
            if (this.page.text !== this.pageText) {
                return confirm('Вы сохранили страницу? Введённые изменения будут утеряны!')
            }

            return true;
        }
    },

    watch: {
        $route(to, from) {
            this.getChapter()
        },

        'page.text'() {
            if (this.page.text) {
                this.page.text = this.page.text.slice(0, 500)
            }
        },

        page() {
            this.pageText = this.page.text
        }
    },

    async mounted() {
        await this.getChapter().then(() => {
            window.onbeforeunload = event =>
            {
                if (this.page.text !== this.pageText) {
                    return confirm("Все несохранённые изменения будут утеряны!");
                }
            };
        })
    },
}
</script>