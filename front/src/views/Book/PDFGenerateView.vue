<template>
    <button @click="print">Print</button>
    <div id="pdf" class="w-full p-0 m-0 bg-white">
        <div class="page w-full flex flex-col justify-center items-center">
            <img class="w-full" :src="payload.cover" alt="">
        </div>

        <div class="page w-full flex flex-col justify-center items-center">
            <h1 class="text-[20pt] text-black font-['Leotaro']">{{ payload.title }}</h1>
            <h2 class="text-[12pt] text-black font-['Leotaro']">{{ payload.subtitle }}</h2>
        </div>

        <div class="page w-full flex flex-col items-center justify-center p-[64px]">
            <h1 class="text-[20pt] text-black font-['Leotaro'] text-center w-full">СОДЕРЖАНИЕ</h1>
            <div v-for="chapter in chapters" :key="chapter.title" class="flex justify-between items-end w-full my-2">
                <p class="font-bold text-[12pt] text-black font-[Montserrat] leading-none">{{ chapter.title }}</p>
                <span class="dotted"></span>
                <p class="font-bold text-[12pt] text-black font-[Montserrat] leading-none">{{ chapter.page }}</p>
            </div>
        </div>

        <div
            class="page w-full p-[64px] flex flex-col"
            v-for="page in payload.pages"
            :key="page.id"
        >
            <div class="flex h-[10%]"
                 :class="{'justify-start text-start' : page.page_number % 2 !== 0, 'justify-end text-end' : page.page_number % 2 === 0 }">
                <p class="text-black font-['Leotaro'] text-[17pt] text-end leading-none">
                    {{ page.page_number % 2 !== 0 ? payload.title : payload.subtitle }}</p>
            </div>
            <div class="h-[6%]">
                <p class="text-black font-black font-[Montserrat] text-[20pt] leading-tight text-center mb-4"
                   v-if="page.title">
                    {{ page.title }}</p>
            </div>
            <div class="h-[79%] py-2">
                <p class="text-black font-[Helvetica] text-[17pt] leading-tight text-justify break-words hyphens-auto"
                   v-html="text(page.text)"
                   v-if="page.text"></p>
                <img :src="page.image" alt="" v-else>
            </div>
            <div class="flex justify-center items-center h-[5%]">
                <p class="text-black font-['Leotaro'] text-[17pt]">{{ page.page_number }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import {useBookStore} from "@/stores/book";
import html2pdf from "html2pdf.js/src";
import {useUserStore} from "@/stores/user";
import typo from "ru-typo";

export default {
    name: "PDFGenerateView",

    data: () => ({
        bookStore: useBookStore(),
        userStore: useUserStore(),

        payload: {
            title: null,
            subtitle: null,
            pages: [],
        },

        chapters: [],
    }),

    methods: {
        print() {
            let el = document.getElementById('pdf');

            html2pdf()
                .set({
                    filename: this.userStore.user.name + '.pdf',

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
                .from(el)
                .save()
        },

        text(text) {
            let result = text ?? '';
            result = result.replaceAll('\n', '¡').replaceAll('    ', 'ºººº')
            result = typo(result, {hyphens: true});
            let t = result
            result = result.split(/\s/).filter(res => res !== '');

            let hyphenated = []
            let tmp = ''

            // if (result.length !== 1) {
            for (let word of result) {
                let shards = word.match(/((?!­).){1,34}/g)

                if (shards) {
                    for (let shard of shards) {
                        if (tmp.length + shard.length <= 34) {
                            tmp += shard
                            continue
                        }

                        hyphenated.push(tmp += (shard !== shards[0] ? '-' : ''))
                        tmp = shard
                    }

                    tmp += ' '
                }
            }

            hyphenated.push(tmp)

            hyphenated = hyphenated.join('')
            hyphenated = hyphenated.replaceAll('¡', '<br>').replaceAll('ºººº', '&nbsp;&nbsp;&nbsp;&nbsp;')

            return hyphenated

            // t = t.replaceAll('¡', '<br>').replaceAll('ºººº', '&nbsp;&nbsp;&nbsp;&nbsp;')
            // return t
        },

        async toDataURL(url) {
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
                xhr.open('GET', 'http://api.memoriabook.online/public/api/image?filename=' + filename);
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
        let pages = this.bookStore.chapters.chapters.reduce(function (acc, curr) {
            let pages = curr.pages

            pages[0].title = curr.title

            return acc.concat(curr.pages)
        }, [])

        for (let index in pages) {
            // pages[index].text = this.text(pages[index].text)

            if (pages[index].title) {
                this.chapters.push({
                    title: pages[index].title,
                    page: pages[index].page_number
                })
            }
        }

        this.payload = {
            title: this.bookStore.book.title,
            subtitle: this.bookStore.book.subtitle,
            cover: await this.toDataURL(this.bookStore.book.cover_type.image_link),
            pages: pages
        }

        for (let index in this.payload.pages) {
            if (this.payload.pages[index].image) {
                this.getDataUrl(this.payload.pages[index].image, index)
            }
        }
    }
}
</script>

<style scoped lang="scss">
#pdf {
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