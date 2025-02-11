<template>
    <router-view />
</template>

<script>

export default {
    async beforeMount() {
        let fontSize = window.innerWidth * 16 / 1792;
        document.querySelector('html').style.fontSize = fontSize + 'px';

        if (!localStorage.getItem(process.env.VUE_APP_TOKEN_KEY)) {
            this.$router.push('/login');
        }
    }
}
</script>

<style lang="scss">
</style>
