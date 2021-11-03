<template>
    <section class="min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="alert alert-success">You have been successfully logged out.</div>
                    <router-link class="btn btn-outline-primary" to="/">Return to Home Page</router-link>
                </div>
            </div>
        </div>

    </section>
</template>

<script>
export default {
    created() {
        this.$store.dispatch('logout')
    }
}
</script>