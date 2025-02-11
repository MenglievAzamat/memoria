<template>
    <div class="pt-[7.5rem]">
        <div class="flex justify-between items-center">
            <ClientBlock
                class="w-1/2 mr-[0.375rem]"
                header="Моя анкета.<br>Вводные данные"
                text="Данная анкета необходима для формирования вопросов интервью для вашей книги"
                link="/client/questionnaire"
            />
            <ClientBlock
                class="w-1/2"
                header="Моя книга воспоминаний"
                text="Данный опрос необходим для составления текста для самой книги"
                link="/client/book"
                :locked="!book.open"
            />
        </div>
    </div>
</template>

<script>
import ClientBlock from "@/components/Client/ClientBlock.vue";
import {useUserStore} from "@/stores/user";
import {useBookStore} from "@/stores/book";

export default {
    name: "ClientView",

    components: {ClientBlock},

    data: () => ({
        userStore: useUserStore(),
        bookStore: useBookStore(),
    }),

    computed: {
        user() {
            return this.userStore.user
        },

        book() {
            return this.bookStore.book
        }
    },
}
</script>