<template>
    <div class="memoria-bg h-[28.625rem] rounded relative" :class="{grayscale: locked}">
        <div class="absolute flex justify-center items-center top-[1rem] right-[1.6rem]">
                <p class="text-white text-[0.875rem] mr-[0.5rem]">{{ locked ? 'Недоступен' : 'Доступно для заполнения'}}</p>
                <Icon :type="locked ? 'lock' : 'unlock'" />
        </div>
        <div class="absolute left-[1.375rem] bottom-[2.5625rem] w-[65%]">
            <h1 class="mb-[0.625rem]" v-html="header"></h1>
            <p class="mb-[2.25rem]">{{ text }}</p>
            <button :disabled="locked" @click="toLink">Открыть</button>
        </div>
    </div>
</template>

<script>
import Icon from "@/components/Icon.vue";

export default {
    name: "ClientBlock",
    components: {Icon},

    props: {
        header: {
            type: String,
            required: true
        },

        text: {
            type: String,
            required: true
        },

        link: {
            type: String,
            required: true
        },

        locked: {
            type: Boolean,
            default: false,
        }
    },

    methods: {
        toLink() {
            if (!this.locked) {
                this.$router.push(this.link)
            }
        }
    }
}
</script>