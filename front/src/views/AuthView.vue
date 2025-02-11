<template>
    <div class="memoria-bg
                absolute top-0 bottom-0 right-0 left-0
                flex justify-center items-center">
        <div class="rounded-[1.5rem] w-[38.5625rem] h-[41rem] bg-[rgba(0,0,0,0.5)] backdrop-blur-[15px]
                    px-[4.8125rem]
                    flex flex-col justify-center items-center">
            <img class="w-[18.75rem] h-[4.6875rem] mb-[3.5625rem]" src="@/assets/img/logo.png" alt="logo.png"/>
            <GoldenText text="Вход в личный кабинет"/>
            <input class="mb-[0.5rem]" type="text" placeholder="Введите логин" v-model="credentials.phone">
            <input class="mb-[2.75rem]" type="password" placeholder="Введите пароль" v-model="credentials.password">
            <button @click="login">Войти</button>
        </div>
    </div>
</template>

<script>
import GoldenText from "@/components/GoldenText.vue";
import {useUserStore} from "@/stores/user";

export default {
    name: "AuthView",

    components: {GoldenText},

    data: () => ({
        store: useUserStore(),

        credentials: {
            phone: null,
            password: null,
        }
    }),

    methods: {
        login() {
            this.store.login(this.credentials)
        }
    },

    beforeMount() {
        if (localStorage.getItem(process.env.VUE_APP_TOKEN_KEY)) {
            this.$router.push('/');
        }
    }
}
</script>