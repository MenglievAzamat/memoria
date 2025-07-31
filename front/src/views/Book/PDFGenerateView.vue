<template>
    <Loading v-if="loading" text="Генерируется PDF файл, ожидайте..."/>
    <div v-else>
        <button @click="print">Print</button>
        <div id="pdf-0" class="pdf w-full p-0 m-0 bg-white">
            <div class="page w-full flex flex-col justify-center items-center">
                <img class="w-full" :src="payload.cover" alt="">
            </div>

            <div class="page w-full flex flex-col justify-center items-center p-[62px]">
                <h1 class="text-[20pt] text-black font-['Leotaro'] text-center">{{ payload.title }}</h1>
                <h2 class="text-[12pt] text-black font-['Leotaro'] text-center">{{ payload.subtitle }}</h2>
            </div>

            <div class="page w-full flex flex-col items-center justify-center p-[62px]">
                <h1 class="text-[20pt] text-black font-['Leotaro'] text-center w-full mb-[16px]">СОДЕРЖАНИЕ</h1>
                <div v-for="chapter in chapters" :key="chapter.title" class="flex justify-between items-center w-full my-2">
                    <p class="font-bold text-[12pt] text-black font-[Montserrat] leading-none text-start">{{ chapter.title }}</p>
                    <span class="dotted"></span>
                    <p class="font-bold text-[12pt] text-black font-[Montserrat] leading-none">{{ chapter.page }}</p>
                </div>
            </div>
        </div>
        <div v-for="(pages, index) in payload.pages" :id="`pdf-${index+1}`" class="pdf w-full p-0 m-0 bg-white">
            <div
                class="page w-full p-[62px] flex flex-col"
                v-for="(page, index) in pages"
                :key="index"
            >
                <div class="flex h-[10%]"
                     :class="{'justify-start text-start' : page.page % 2 !== 0, 'justify-end text-end' : page.page % 2 === 0 }">
                    <p class="text-black font-['Leotaro'] text-[17pt] text-end leading-none">
                        {{ page.page % 2 !== 0 ? payload.title : payload.subtitle }}</p>
                </div>
                <div class="h-[85%] py-2">
                    <p class="text-black font-black font-[Montserrat] text-[20pt] leading-tight text-center mb-4"
                       v-if="page.title">
                        {{ page.title }}</p>
                    <div class="flex" :class="{'justify-between': wrapLine(line) > threshold }"
                         v-for="(line, index) in page.text">
                        <p :class="{'mr-3' : wrapLine(line) <= threshold}"
                           class="helvetica leading-[125%] text-[17pt] text-black" v-for="word in line" v-html="word"></p>
                    </div>
                </div>
                <div class="flex justify-center items-center h-[5%]">
                    <p class="text-black font-['Leotaro'] text-[17pt]">{{ page.page }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useBookStore} from "@/stores/book";
import html2pdf from "html2pdf.js/src";
import {useUserStore} from "@/stores/user";
import {pageFormatter, chapterDividerV2, wrapLine, threshold} from "@/plugins/helpers";
import Loading from "@/components/Loading.vue";


export default {
    name: "PDFGenerateView",
    components: {Loading},

    data: () => ({
        bookStore: useBookStore(),
        userStore: useUserStore(),

        payload: {
            title: null,
            subtitle: null,
            pages: [],
        },

        chapters: [],

        loading: false,

        threshold: 25,
    }),

    methods: {
        wrapLine(line) {
            return line.reduce((acc, curr) => acc + curr.length, 0)
        },

        print() {
            this.loading = true

            let els = [
                document.getElementById('pdf-0')
            ];

            for (let index in this.payload.pages) {
                els.push(document.getElementById(`pdf-${+index+1}`))
            }

            for (let i in els) {
                html2pdf()
                    .set({
                        filename: this.bookStore.book.user.name + `${i}.pdf`,

                        image: {
                            type: "jpeg",
                            quality: 0.98
                        },

                        html2canvas: {
                            dpi: 192,
                            letterRendering: true,
                            allowTaint: true,
                        },

                        jsPDF: {
                            format: 'a5'
                        },
                    })
                    .from(els[i])
                    .save()
            }

            this.loading = false
        },

        toDataURL(url) {
            return new Promise((resolve, reject) => {
                let filename = url.split('/').splice(5).join('/')

                let xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    let reader = new FileReader();
                    reader.onloadend = function () {
                        resolve(reader.result);
                    }
                    reader.readAsDataURL(xhr.response);
                };
                xhr.open('GET', 'https://api.memoriabook.online/public/api/image?filename=' + filename);
                // xhr.open('GET', 'http://memoria.test/api/image?filename=' + filename);
                xhr.responseType = 'blob';
                xhr.send();
            })
        },

        async getDataUrl(url, index) {
            this.payload.pages[index].image = await this.toDataURL(url)
        }
    },

    async mounted() {
        this.loading = true;

        let chapters = this.bookStore.chapters.chapters;
        let pages = []

        for (let index in chapters) {
            // let divided = chapterDivider(chapters[index].text)
            let divided = chapterDividerV2(chapters[index].text)

            pages.push(...pageFormatter(divided, chapters[index].title, chapters[index].last_page ?? 0))

            if (chapters[index].title) {
                this.chapters.push({
                    title: chapters[index].title,
                    page: (chapters[index].last_page + 1 ?? 1)
                })
            }
        }

        for (let index in pages) {
            if (typeof pages[index].text[0] != 'undefined' && pages[index].text[0][0]?.includes('<img')) {
                let match = pages[index].text[0][0].match(/(http|https):\/\/.+\.\w+/g)
                let newUrl = await this.toDataURL(match[0])

                pages[index].text[0][0] = pages[index].text[0][0].replace(/(http|https):\/\/.+\.\w+/, newUrl)
            }
        }

        let chunkedPages = []

        while (pages.length > 0) {
            chunkedPages.push(pages.splice(0, 30))
        }

        this.payload = {
            title: this.bookStore.book.title,
            subtitle: this.bookStore.book.subtitle,
            cover: await this.toDataURL(this.bookStore.book.cover_type.image_link),
            pages: chunkedPages
        }

        this.loading = false;
    }
}
</script>

<style scoped lang="scss">
.pdf {
    width: 5.83in;
}

.page {
    width: 5.83in;
    height: 8.27in;
}

.dotted {
    border-bottom: 1px dotted black;
    flex-grow: 1;
}

h1 {
    line-height: 28px !important;
}
</style>