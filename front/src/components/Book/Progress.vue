<template>
    <div class="relative overflow-hidden w-full h-[1.125rem] rounded-full">
        <div class="absolute w-full h-full bg-white"></div>
        <div class="absolute w-full h-full bg-[#E3B661] rounded-full" :style="`left:-${100 - progress}%`"></div>
    </div>
</template>

<script>
export default {
    name: "Progress",

    props: {
        progress: {
            type: Number,
            required: true
        }
    }
}
</script>

<style scoped>
* {
    transition: .5s ease-in-out;
}
</style>