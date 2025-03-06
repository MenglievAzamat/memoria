<template>
    <Loading v-if="loading"/>
    <div v-else>
        <p v-if="user.type !== 'admin'" class="leading-none text-[1.5rem] mb-10">{{ chapter?.question }}</p>
        <div v-else>
            <div class="flex">
                <input class="text-[1.5rem] mb-4 h-[2.9375rem] !rounded-r-none" type="text" v-model="chapter.title"
                       placeholder="Заголовок...">
            </div>
            <div class="flex">
                <input class="text-[1.5rem] mb-10 h-[2.9375rem] !rounded-r-none" type="text" v-model="chapter.question"
                       placeholder="Вопрос...">
                <button class="!rounded-l-none" @click="saveQuestion">Сохранить</button>
            </div>
        </div>

        <p class="mb-4"></p>
        <textarea
            class="mb-4"
            rows="8"
            placeholder="Введите текст..."
            v-model="chapter.text"
            @input="updateCursorPosition"
            @click="updateCursorPosition"
            @keyup="updateCursorPosition"
        ></textarea>
        <div class="flex justify-between mb-8">
            <div class="flex items-start">
                <button class="mr-2" @click="saveChapter">Сохранить</button>
                <button class="mr-2" @click="startUpload" :disabled="cursorPosition === null">
                    <Icon class="mr-1" type="folder" color="black"/>
                    Добавить фото
                </button>
                <input id="fileInput" type="file" hidden @input="makeInput">
            </div>
        </div>

        <Pagination
            :page="currentPage + offset"
            :total_pages="totalPages"
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
import {chapterDivider, chapterDividerV2, pageFormatter} from "@/plugins/helpers";

export default {
    name: "ChapterView",
    components: {Pagination, Loading, Preview, Icon},

    data: () => ({
        bookStore: useBookStore(),
        userStore: useUserStore(),
        adminStore: useAdminStore(),

        chapter: {
            title: null,
            question: null,
            text: null,
        },

        pages: [],
        pagesFormatted: [],
        currentPage: 1,
        offset: 0,
        cursorPosition: null,

        image: null,

        loading: false,
        chapterText: null,
    }),

    computed: {
        totalPages() {
            return (this.pages.length > 0 ? this.pages.length : 1) + this.offset
        },

        user() {
            return this.userStore.user
        },

        previewPayload() {
            let leftPage, rightPage
            let pageNumber = this.currentPage + this.offset
            let defaultPage = {
                title: null,
                text: null,
                page: null,
            }

            if (pageNumber % 2 === 0) {
                if (this.pagesFormatted[this.currentPage - 2]) {
                    leftPage = this.pagesFormatted[this.currentPage - 2]
                } else {
                    leftPage = defaultPage
                }

                rightPage = this.pagesFormatted[this.currentPage - 1]
            } else {
                if (this.pagesFormatted[this.currentPage]) {
                    rightPage = this.pagesFormatted[this.currentPage]
                } else {
                    rightPage = defaultPage
                }


                leftPage = this.pagesFormatted[this.currentPage - 1]
            }

            return [leftPage, rightPage]
        },
    },

    methods: {
        startUpload() {
            document.querySelector('#fileInput').click()
        },

        async makeInput() {
            alert('Каждая добавленная фотография займет целую страницу. ' +
                'В текстовой строке появится "ссылка" на фото — она должна находиться в новой строке. ' +
                'Чтобы удалить фотографию, просто удалите текст "ссылки".')

            const input = document.querySelector('#fileInput')
            const files = input.files

            if (files && files[0]) {
                const reader = new FileReader()

                // reader.onload = (e) => {
                //     this.image = e.target.result
                // }

                reader.readAsDataURL(files[0])
                this.image = files[0]
                this.$emit('input', files[0])

                await this.bookStore.addImage(this.chapter.id, this.image).then(url => {
                    let tmp

                    if (this.cursorPosition !== 0) {
                        tmp = [this.chapter.text.substring(0, this.cursorPosition), this.chapter.text.substring(this.cursorPosition)]
                    } else {
                        tmp = ['', '']
                    }

                    this.chapter.text = tmp[0] + `<img src="${url}" alt="image"/>` + tmp[1]
                })
            }
        },

        async getChapter() {
            this.loading = true

            await this.bookStore.getChapter(this.$route.params.id)
                .then(response => {
                    this.chapter = response.chapter
                    this.offset = response.pc_last_page ?? 0
                    this.currentPage = 1
                    this.chapterText = response.chapter.text
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

        async saveChapter() {
            this.loading = true

            await this.bookStore.saveChapter(this.chapter.id, this.chapter.text)
                .finally(() => {
                    location.reload()
                    this.loading = false
                })
        },

        nextPage() {
            if (this.currentPage !== this.totalPages - this.offset) {
                this.currentPage++
            }
        },

        previousPage() {
            if (this.currentPage !== 1) {
                this.currentPage--
            }
        },

        canUpdate() {
            if (this.chapter.text !== this.chapterText) {
                return confirm('Вы сохранили страницу? Введённые изменения будут утеряны!')
            }

            return true;
        },

        updateCursorPosition(e) {
            let target = e.target

            this.cursorPosition = target.selectionStart
        }
    },

    watch: {
        async $route(to, from) {
            await this.getChapter()
        },

        'chapter.text'() {
            // this.pages = chapterDivider(this.chapter.text)
            this.pages = chapterDividerV2(this.chapter.text)
        },

        pages() {
            this.pagesFormatted = pageFormatter(this.pages, this.chapter.title, this.offset)
        },

        image() {
            console.log(this.image)
        },

        cursorPosition() {
            let symbols = 0
            let page = null;

            for (let i in this.pages) {
                for (let j in this.pages[i]) {
                    for (let k in this.pages[i][j]) {
                        if (symbols > this.cursorPosition) {
                            page = i
                            break
                        }

                        symbols += this.pages[i][j][k].length + 1
                    }

                    if (page !== null) {
                        break
                    }
                }

                if (page !== null) {
                    break
                }
            }

            this.currentPage = +page + 1
        }
    },

    async mounted() {
        await this.getChapter()

        document.addEventListener('keydown', event => {
            if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                // Отменяем стандартное поведение (сохранение страницы)
                event.preventDefault();

                this.saveChapter()
            }
        })

        // window.onbeforeunload = event => {
        //     return this.canUpdate()
        // };
    },

    beforeRouteUpdate(to, from, next) {
        if (this.canUpdate()) {
            next()
        } else {
            next(false)
        }
    },

    beforeRouteLeave(to, from, next) {
        if (this.canUpdate()) {
            next()
        } else {
            next(false)
        }
    }
}
</script>