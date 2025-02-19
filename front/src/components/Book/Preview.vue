<template>
    <div class="leotaro relative w-full h-[47.5rem] text-center flex justify-end items-center mb-[3.75rem]">
        <div v-if="payload.title" class="w-1/2 pr-[3rem] pl-[1.5rem] z-10">
            <h1 class="text-black text-wrap text-[1.66625rem] font-['Leotaro']">{{ payload.title }}</h1>
            <h2 class="text-[0.99975rem] font-['Leotaro']">{{ payload.subtitle }}</h2>
        </div>

        <div v-else class="helvetica w-full h-full px-[1.5rem] z-10 flex">
            <div class="w-1/2 h-full flex flex-col justify-center p-[4rem]">
                <div class="header h-[10%]">
                    <p class="text-black text-wrap text-[1.66625rem] font-['Leotaro'] text-left">{{ book.title }}</p>
                </div>
                <div class="body h-[85%]">
                    <p v-if="payload[0]?.title" class="font-[Montserrat] text-black text-[1.66625rem] text-center mb-[.4rem] font-bold">{{ payload[0]?.title }}</p>
                    <img v-if="payload[0]?.image" class="w-full h-full" :src="payload[0]?.image" alt="">
                    <p class="break-words hyphens-auto helvetica text-[1.4163125rem] text-black text-justify" v-html="text(payload[0]?.text)"></p>
                </div>
                <div class="footer h-[5%]">
                    <p class="helvetica text-[1.4163125rem] text-black">{{ payload[0] ? payload[0].page : ''}}</p>
<!--                    <p class="helvetica text-[1.4163125rem] text-black">{{ payload[0]?.text?.length}}</p>-->
                </div>
            </div>
            <div class="w-1/2 h-full flex flex-col justify-center p-[4rem]">
                <div class="header h-[10%]">
                    <p class="text-black text-wrap text-[1.66625rem] font-['Leotaro'] text-right">{{ book.subtitle }}</p>
                </div>
                <div class="body h-[85%]">
                    <p v-if="payload[1]?.title" class="font-[Montserrat] text-black text-[1.66625rem] text-center mb-[.4rem] font-bold">{{ payload[1]?.title }}</p>
                    <img v-if="payload[1]?.image" class="w-full h-full" :src="payload[1]?.image" alt="">
                    <p class="break-words hyphens-auto helvetica text-[1.4163125rem] text-black text-justify" v-html="text(payload[1]?.text)"></p>
                </div>
                <div class="footer h-[5%]">
                    <p class="helvetica text-[1.4163125rem]  text-black">{{ payload[1] ? payload[1].page : ''}}</p>
<!--                    <p class="helvetica text-[1.4163125rem]  text-black">{{ payload[1]?.text?.length}}</p>-->
                </div>
            </div>
        </div>

        <img class="absolute right-0 left-0 top-0 bottom-0 z-0" src="@/assets/img/book.png" alt="book.png">
    </div>
</template>

<script>
import {useBookStore} from "@/stores/book";
import typo from "ru-typo";

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
        bookStore: useBookStore()
    }),

    computed: {
        book() {
            return this.bookStore.book
        }
    },

    methods: {
        text(text) {
            let result = text ? text.replaceAll('\n', '<br>').replaceAll('    ', '&nbsp;&nbsp;&nbsp;&nbsp;') : '';

            return typo(result, { hyphens: true });
        }
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
</style>