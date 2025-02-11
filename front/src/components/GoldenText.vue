<template>
    <div class="flex flex-col items-center justify-center">
        <img class="w-[20.3125rem] h-[1.1875rem] mb-[1.25rem]" src="@/assets/img/gold.png" alt="gold.png">
        <p class="text-gold mb-[1.4375rem]">{{ text }}</p>
    </div>
</template>

<script>
export default {
    name: "GoldenText",

    props: {
        text: {
            type: String,
            required: true
        }
    }
}
</script>

<style scoped>
p {
    font-family: 'Leotaro', sans-serif;
    line-height: 1;
}
</style>