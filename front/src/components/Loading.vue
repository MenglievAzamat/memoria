<template>
    <div class="texture-bg absolute top-0 bottom-0 right-0 left-0 z-10
                flex justify-center items-center">
        <div>
            <img class="w-[18.75rem] h-[4.75rem] animate-bounce" src="@/assets/img/logo.png" alt="">
            <p v-if="text">{{ text }}</p>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        text: {
            required: false
        }
    }
}
</script>