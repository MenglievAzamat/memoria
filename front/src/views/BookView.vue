<template>
    <div class="texture-bg absolute top-0 bottom-0 right-0 left-0 overflow-hidden">
        <Loading v-if="loading"/>
        <div class="body flex h-full" v-else>
            <div class="w-[22.9375rem]">
                <Sidebar/>
            </div>
            <div
                class="body-content w-[calc(100%-22.9375rem)] pt-[5.3125rem] pl-[4.875rem] pr-[14.75rem] overflow-scroll">
                <div class="flex justify-between items-start">
                    <GoldenText :text="topText"/>
                    <UserButtons/>
                </div>
                <router-view/>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "@/components/Book/Sidebar.vue";
import Icon from "@/components/Icon.vue";
import UserButtons from "@/components/UserButtons.vue";
import GoldenText from "@/components/GoldenText.vue";
import {useBookStore} from "@/stores/book";
import {useLoadingStore} from "@/stores/loading";
import {useUserStore} from "@/stores/user";
import Loading from "@/components/Loading.vue";

export default {
    name: "BookView",

    components: {Loading, GoldenText, UserButtons, Icon, Sidebar},

    data: () => ({
        bookStore: useBookStore(),
        loadingStore: useLoadingStore(),
        userStore: useUserStore(),
    }),

    computed: {
        topText() {
            return this.$route.href === '/client/book/review' ? 'Просмотр книги' : 'Заполнение книги';
        },

        loading() {
            return this.loadingStore.loading
        }
    },

    async mounted() {
        this.loadingStore.loadingOn()

        await this.userStore.fetchUser().then(async () => {
            let handler

            if (this.userStore.user.type === 'admin') {
                let bookId = JSON.parse(localStorage.getItem('admin')).bookId

                handler = this.bookStore.fetchBookById(bookId)
            } else {
                handler = this.bookStore.fetchBookByUserId(this.userStore.user.id)
            }

            await handler.then(async () => {
                await this.bookStore.getChapters(this.bookStore.book.id).then(() => {
                    if (this.$route.path === '/client/book') {
                        this.$router.push('/client/book/cover');
                    }
                })
            })
        }).finally(() => {
            this.loadingStore.loadingOff()
        })
    }
}
</script>