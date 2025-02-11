<template>
    <div
        class="bg-[#1B1B1B] menu-item w-full pl-[4.0625rem] py-[1.25rem] text-white mb-1 text-[.875rem]"
        :class="{'hover:bg-[#2A2A2A] cursor-pointer':!disabled, 'font-bold': disabled, 'bg-[#2A2A2A]':active}"
        @click="goTo"
    >
        {{ text }}
    </div>
</template>

<script>
export default {
    name: "MenuItem",

    props: {
        text: {
            type: String,
            required: true,
        },

        disabled: {
            type: Boolean,
            default: false,
        },

        to: {
            type: String,
            default: '#',
        }
    },

    computed: {
        active() {
            return this.$route.href === this.to;
        }
    },

    methods: {
        goTo() {
            if (!this.disabled) {
                this.$router.push(this.to);
            }
        },
    },
}
</script>