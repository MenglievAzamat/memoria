<template>
    <div class="flex justify-center items-center">
        <div
            class="hover:scale-110 cursor-pointer"
            @click="$emit('prevPage')"
        ><Icon type="arrow_left" /></div>
        <div class="w-[5.625rem] h-[2.0625rem] bg-[#D9D9D9] rounded flex justify-center items-center text-[1.125rem] mx-4">{{ page }} / {{ total_pages }}</div>
        <div class="hover:scale-110 cursor-pointer" @click="$emit('nextPage')"><Icon type="arrow_right" /></div>
    </div>
</template>

<script>
import Icon from "@/components/Icon.vue";

export default  {
    name: "Pagination",
    components: {Icon},

    props: {
        page: {
            type: Number,
            required: true
        },

        total_pages: {
            type: Number,
            required: true
        }
    }
}
</script>

<style scoped>
svg {
    width: 2.0625rem !important;
    height: 2.0625rem !important;
}
</style>