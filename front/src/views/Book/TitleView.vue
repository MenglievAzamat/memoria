<template>
    <div>
        <p class="text-[1.5rem] mb-[1.6875rem]">Введите текст титульного листа</p>

        <textarea class="mb-[0.6875rem]" v-model="form.title" placeholder="Заголовок..."></textarea>

        <textarea class="mb-[0.6875rem]" v-model="form.subtitle" placeholder="Подзаголовок..."></textarea>
        <button class="mb-[1.125rem]" @click="saveTitle">Сохранить</button>

        <Preview type="cover" :payload="form"/>
    </div>
</template>

<script>
import Preview from "@/components/Book/Preview.vue";
import {useBookStore} from "@/stores/book";

export default {
    name: "TitleView",

    components: {Preview},

    data: () => ({
        bookStore: useBookStore(),

        form: {
            title: null,
            subtitle: null,
        }
    }),

    methods: {
        saveTitle() {
            this.bookStore.saveTitle(this.bookStore.book.id, this.form)
        }
    },

    mounted() {
        if (this.bookStore.book) {
            this.form = {
                title: this.bookStore.book.title,
                subtitle: this.bookStore.book.subtitle,
            }
        }
    }
}
</script>