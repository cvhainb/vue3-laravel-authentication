<template>
    <template v-if="isUserToken">
        <layout-header></layout-header>
        <layout-sidebar></layout-sidebar>
        <div id="user-panel" class="user-panel">
            <router-view></router-view>
        </div>
        <element-toast></element-toast>
    </template>
</template>

<script>
import LayoutHeader from '@/components/storefront/templates/layout/Header'
import LayoutSidebar from '@/components/storefront/templates/layout/Sidebar'
import ElementToast from '@/components/storefront/templates/element/Toast'

import { mapGetters } from 'vuex';
export default {
    components: { LayoutHeader, LayoutSidebar, ElementToast },
    created() {
        if(!this.isUserToken) {
            this.$router.push('/login')
        }
    },
    mounted() {
        const toggleBtn = document.querySelector('.toggle-sidebar-btn')
        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                document.querySelector('body').classList.toggle('toggle-sidebar')
            })
        }
    },
    computed: {
        ...mapGetters(['isUserToken']),
    }
}
</script>