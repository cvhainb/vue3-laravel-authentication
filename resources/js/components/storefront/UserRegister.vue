<template>
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div v-if="!isUserToken" class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="d-flex justify-content-center py-4">
                        <router-link to="/" class="logo d-flex align-items-center w-auto text-decoration-none">
                            <img src="assets/img/logo.png" alt="">
                            <span class="d-none d-lg-block">MCFeede</span>
                        </router-link>
                    </div><!-- End Logo -->
                    
                    <div class="card card-body shadow-sm w-100">
                        <div class="card-title h5">Create a new account</div>
                        <p v-if="errorMsg" class="text-danger">{{ errorMsg }}</p>
                        <form @submit.prevent="register()">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input v-model="formdata.name" type="text" class="form-control" id="Name" placeholder="E.g. John Wick" required>
                            </div>
                            <div class="mb-3">
                                <label for="email-address" class="form-label">Email address</label>
                                <input v-model="formdata.email" type="email" class="form-control" id="email-address" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input v-model="formdata.password" type="password" class="form-control" id="password" placeholder=" Password " required>
                            </div>
                            <div class="text-end">
                                <button :disabled="processing === true" class="btn btn-primary" type="submit">
                                    <template v-if="processing === true">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </template>
                                    <template v-else>
                                        Register
                                    </template>
                                </button>
                            </div>
                            <hr class="border-light">
                            <p class="text-center">Already have an account? <router-link to="/login">Login here</router-link>.</p>
                        </form>
                    </div>
                </div>
            </div>
            <div v-else class="row">
                <div class="col-12 text-center">
                    <p>You have already logged into the system. Please go to the <router-link to="/account/zuc/dashboard">MCFeede Dashboard</router-link> or back to the <router-link to="/">home page</router-link>.</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data: () => ({
        formdata: {
            name: undefined,
            email: undefined,
            password: undefined,
        },
        processing: false,
        errorMsg: undefined
    }),
    methods: {
        register() {
            this.processing = true
            this.$store.dispatch('register', this.formdata).then(() => {
                this.$router.push('/store/dashboard')
            }).catch(error => {
                this.errorMsg = error.response.data.message
            })
            .finally(() => {
                this.processing = false
            })
        }
    },
    computed: {
        ...mapGetters(['isUserToken']),
    }
}
</script>