<template>
    <div>
        <div class="flex justify-end items-center pt-4">
            <button class="animate-bounce" @click="videoPopup = true">
                <Icon type="play" class="mr-1"/>
                Инструкция
            </button>
        </div>
        <div class="flex justify-between items-center pt-[7.5rem]">
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

        <div class="absolute top-0 bottom-0 right-0 left-0 flex justify-center items-center" v-if="videoPopup">
            <div class="background absolute w-full h-full bg-black/80 z-9" @click="videoPopup = false"></div>
            <div class="body w-2/3 bg-black p-8 z-10 rounded">
                <iframe frameborder="0" allowfullscreen="" scrolling="no" allow="autoplay;fullscreen"
                        src="https://onelineplayer.com/player.html?autoplay=false&autopause=true&muted=false&loop=true&url=https%3A%2F%2Fwww.dropbox.com%2Fscl%2Ffi%2F7vciuxl3jym38wnw1v5d5%2FHow-to-Memoria.mp4%3Frlkey%3Dsd46w1z94edcrke5nf4xgqb0c%26st%3Dn102ek86%26raw%3D1&poster=&time=true&progressBar=true&overlay=true&muteButton=true&fullscreenButton=true&style=light&quality=auto&playButton=true"
                        style="height: 100%; width: 100%; aspect-ratio: 3840 / 2160;"></iframe>

            </div>
        </div>
    </div>
</template>

<script>
import ClientBlock from "@/components/Client/ClientBlock.vue";
import {useUserStore} from "@/stores/user";
import {useBookStore} from "@/stores/book";
import Icon from "@/components/Icon.vue";

export default {
    name: "ClientView",

    components: {Icon, ClientBlock},

    data: () => ({
        userStore: useUserStore(),
        bookStore: useBookStore(),

        videoPopup: false,
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