<template>
    <div>
        <p class="text-[1.5rem] mb-[4.6875rem]">Выберите цвет обложки</p>

        <div class="flex flex-wrap">
            <div
                v-for="cover in covers"
                :key="cover.id"
                class="w-1/2 pr-[2.875rem] mb-[2.875rem] cursor-pointer hover:scale-[1.02] transition"
                :class="{'animate-pulse': cover.id === store.book.cover_type_id}"
                @click="choose(cover.id)"
            >
                <img class="w-full" :src="cover.image_link" alt="book">
            </div>
        </div>
    </div>
</template>

<script>

import {useBookStore} from "@/stores/book";

export default {
    name: "CoverView",

    data: () => ({
        store: useBookStore(),

        covers: []
    }),

    methods: {
        async getCovers() {
            await this.store.getCovers().then(() => {
                this.covers = this.store.covers
            })
        },

        choose(coverId) {
            this.store.saveCover(this.store.book.id, coverId)
            this.$router.push('/client/book/title')
        }
    },

    mounted() {
        this.getCovers()
    }
}
</script>