<template>
    <div class="texture-bg absolute top-0 bottom-0 right-0 left-0 overflow-hidden px-[8.8125rem]">
        <!--        <Circle class="absolute bottom-0 left-[-30%]"/>-->
        <!--        <Circle class="absolute top-0 right-[-30%]"/>-->

        <div class="body w-full h-full pt-[4.75rem] overflow-scroll">
            <div class="header flex justify-between items-center">
                <img src="@/assets/img/logo.png" alt="logo.png">
                <UserButtons/>
            </div>

            <div class="content">
                <Loading v-if="loading" />
                <router-view v-else />
            </div>
        </div>
    </div>
</template>

<script>
import Circle from "@/components/Circle.vue";
import Icon from "@/components/Icon.vue";
import UserButtons from "@/components/UserButtons.vue";
import {useUserStore} from "@/stores/user";
import {useBookStore} from "@/stores/book";
import Loading from "@/components/Loading.vue";
import {useLoadingStore} from "@/stores/loading";

export default {
    name: "HomeView",

    components: {Loading, UserButtons, Icon, Circle},

    data: () => ({
        userStore: useUserStore(),
        bookStore: useBookStore(),
        loadingStore: useLoadingStore()
    }),

    computed: {
        user() {
            return this.userStore.user
        },

        loading() {
            return this.loadingStore.loading
        }
    },

    watch: {
        '$route.path'() {
            if (this.$route.path === '/') {
                if (this.user.type === 'client') {
                    this.$router.push('/client');
                } else if (this.user.type === 'admin') {
                    this.$router.push('/admin');
                }
            }
        }
    },

    async mounted() {
        this.loadingStore.loadingOn()

        await this.userStore.fetchUser().then(async () => {
            await this.bookStore.fetchBookByUserId(this.user.id).then(() => {
                if (this.$route.path === '/') {
                    if (this.user.type === 'client') {
                        this.$router.push('/client');
                    } else if (this.user.type === 'admin') {
                        this.$router.push('/admin');
                    }
                }
            })
        }).finally(() => {
            this.loadingStore.loadingOff()
        })
    }
}
</script>