<template>
    <div class="buttons flex items-center">
        <div class="help relative">
            <button id="1" class="mr-[0.6875rem] z-20" @click="help = !help"><Icon type="message" />Помощь</button>
            <a href="https://t.me/MEMORIAbook" target="_blank">
                <button id="2" :class="{'top-[3.4375rem]': help, 'hidden top-0': !help}" class="absolute mr-[0.6875rem] z-20">
                    Telegram
                </button>
            </a>
            <a href="https://wa.me/79179008892" target="_blank">
                <button id="3" :class="{'top-[6.875rem]': help, 'hidden top-0': !help}" class="absolute mr-[0.6875rem] z-10">
                    WhatsApp
                </button>
            </a>
        </div>
        <button @click="logout"><Icon type="profile"/>Выйти</button>
    </div>
</template>

<script>
import Icon from "@/components/Icon.vue";
import {useUserStore} from "@/stores/user";

export default {
    name: "UserButtons",

    components: {Icon},

    data: () => ({
        store: useUserStore(),
        help: false
    }),

    methods: {
        async logout() {
            await this.store.logout();
        }
    }
}
</script>

<style>
button {
    transition: .2s ease-in-out;
}
</style>