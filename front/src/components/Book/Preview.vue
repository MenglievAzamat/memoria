<template>
    <div class="leotaro relative w-full h-[47.5rem] text-center flex justify-end items-center mb-[3.75rem]">
        <div v-if="payload.title" class="w-1/2 pr-[3rem] pl-[1.5rem] z-10">
            <h1 class="text-black text-wrap text-[1.66625rem] font-['Leotaro']">{{ payload.title }}</h1>
            <h2 class="text-[0.99975rem] font-['Leotaro']">{{ payload.subtitle }}</h2>
        </div>

        <div v-else-if="payload.review" class="w-full h-full px-[1.5rem] z-10 flex">
            <div class="w-1/2 h-full flex flex-col justify-center p-[3.8rem]">
            </div>
            <div class="w-1/2 h-full flex flex-col justify-start p-[3.8rem]">
                <h1 class="text-black text-wrap text-[1.66625rem] font-['Leotaro']">Содержание</h1>
                <div class="flex justify-between items-end" v-for="item in payload.chapters">
                    <h2 class="text-[0.99975rem] helvetica text-black">{{ item.title }}</h2>
                    <span class="bb"></span>
                    <h2 class="text-[0.99975rem] helvetica">{{ (item.last_page ?? 0) + 1 }}</h2>
                </div>
            </div>
        </div>

        <div v-else class="w-full h-full px-[1.5rem] z-10 flex">
            <div class="w-1/2 h-full flex flex-col justify-center p-[3.8rem]">
                <div class="header h-[10%]">
                    <p class="text-black text-wrap text-[1.66625rem] font-['Leotaro'] text-left">{{ book.title }}</p>
                </div>
                <div class="body h-[85%]">
                    <p v-if="payload[0]?.title"
                       class="font-[Montserrat] text-black text-[1.66625rem] text-center mb-[.4rem] font-bold">
                        {{ payload[0]?.title }}</p>
                    <img v-if="payload[0]?.image" class="w-full h-full" :src="payload[0]?.image" alt="">
                    <!--                    <p class="break-words hyphens-auto font-['GoMono'] text-[1.4163125rem] text-black text-justify" v-html="payload[0]?.text"></p>-->
                    <div class="flex" :class="{'justify-between': wrapLine(line) > threshold }"
                         v-for="(line, index) in payload[0]?.text">
                        <p :class="{'mr-3' : wrapLine(line) <= threshold}"
                           class="helvetica text-[1.4163125rem] text-black" v-for="word in line" v-html="word"></p>
                    </div>
                </div>
                <div class="footer h-[5%]">
                    <p class="helvetica text-[1.4163125rem] text-black">{{ payload[0] ? payload[0].page : '' }}</p>
                </div>
            </div>
            <div class="w-1/2 h-full flex flex-col justify-center p-[3.8rem]">
                <div class="header h-[10%]">
                    <p class="text-black text-wrap text-[1.66625rem] font-['Leotaro'] text-right">{{
                            book.subtitle
                        }}</p>
                </div>
                <div class="body h-[85%]">
                    <p v-if="payload[1]?.title"
                       class="font-[Montserrat] text-black text-[1.66625rem] text-center mb-[.4rem] font-bold">
                        {{ payload[1]?.title }}</p>
                    <img v-if="payload[1]?.image" class="w-full h-full" :src="payload[1]?.image" alt="">
                    <!--                    <p class="break-words hyphens-auto font-['GoMono'] text-[1.4163125rem] text-black text-justify" v-html="payload[1]?.text"></p>-->
                    <div class="flex" :class="{'justify-between': wrapLine(line) > threshold }"
                         v-for="(line, index) in payload[1]?.text">
                        <p :class="{'mr-3' : wrapLine(line) <= threshold}"
                           class="helvetica text-[1.4163125rem] text-black" v-for="word in line" v-html="word"></p>
                    </div>
                </div>
                <div class="footer h-[5%]">
                    <p class="helvetica text-[1.4163125rem]  text-black">{{ payload[1] ? payload[1].page : '' }}</p>
                </div>
            </div>
        </div>

        <img class="absolute right-0 left-0 top-0 bottom-0 z-0" src="@/assets/img/book.png" alt="book.png">
    </div>
</template>

<script>
import {useBookStore} from "@/stores/book";

export default {
    name: "Preview",

    props: {
        payload: {
            type: Object,
        },

        image: {
            type: File,
        }
    },

    data: () => ({
        bookStore: useBookStore(),

        threshold: 25
    }),

    computed: {
        book() {
            return this.bookStore.book
        }
    },

    methods: {
        wrapLine(line) {
            return line.reduce((acc, curr) => acc + curr.length, 0)
        },
    }
}
</script>

<style scoped>
.leotaro {
    font-family: Leotaro, sans-serif !important;
    font-size: 1.375rem !important;
}

.helvetica {
    font-family: Helvetica, sans-serif !important;
    font-size: 1.375rem !important;
    line-height: 125%;
}

.bb {
    border-bottom: 1px dotted black;
    flex-grow: 1;
}
</style>