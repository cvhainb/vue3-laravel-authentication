<template>
    <header v-if="hideHeaderFooter">this is header</header>
    <main>
        <router-view></router-view>
    </main>
    <footer v-if="hideHeaderFooter">this is footer</footer>
</template>

<script>
import storefront from '@/store/storefront'
export default {
    created() {
        Object.keys(storefront).forEach(moduleName => {
            this.$store.registerModule(moduleName, storefront[moduleName])
        })
    }, 
    beforeUnmount() {
        Object.keys(storefront).forEach(moduleName => {
            this.$store.unregisterModule(moduleName)
        })
    },
    computed: {
        hideHeaderFooter() {
            return !['login', 'register', 'logout', 'user_panel'].includes(this.$route.matched[1] && this.$route.matched[1].name === 'user_panel' ? 'user_panel' : this.$route.name)
        }
    }
}
</script>