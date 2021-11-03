<template>
    <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-12 px-3">
                <h4 class="mb-3">Google Merchant Center</h4>
                <div class="card card-body shadow-sm">
                    <div class="d-flex justify-content-center align-items-center py-5">
                        <div class="spinner-border text-info" style="width: 5rem; height: 5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    data: () => ({
        processing: true
    }),
    created() {
        axios.post(`/api/v1/storefront/oauth2/google`, this.$route.query, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        }).finally(() => {
            this.$router.push('/store/google-merchant/setting')
        })
    },
}
</script>