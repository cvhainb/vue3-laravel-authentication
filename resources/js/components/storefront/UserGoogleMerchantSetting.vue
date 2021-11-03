<template>
    <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-7 px-3">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="mb-0">Google Merchant - Configuration</h4>
                    <a href="/google-oauth2" class="btn btn-danger text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                        </svg>
                        Sign in with Google
                    </a>
                </div>
                <div class="card card-body shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-lg-5 mb-3">
                            Merchant Center Account
                        </div>
                        <div class="col-lg-7 mb-3">
                            <select v-model="formdata.accountId" class="form-select" required>
                                <template v-if="processing === true">
                                    <option :value="undefined">Loading...</option>
                                </template>
                                <template v-else>
                                    <option :value="undefined">Select your merchant account id</option>
                                    <option v-for="account in accounts" :key="account.id" :value="account.id">{{ account.name }} - {{ account.id }}</option>
                                </template>
                            </select>
                        </div>
                        <div class="col-lg-5 mb-3">
                            Language
                        </div>
                        <div class="col-lg-7 mb-3">
                            <select v-model="formdata.language" class="form-select" required>
                                <option v-for="lang in languages" :key="lang.id" :value="lang.id">{{ lang.text }}</option>
                            </select>
                        </div>
                        <div class="col-lg-5 mb-3">
                            Target country (ISO Code 2)
                        </div>
                        <div class="col-lg-7 mb-3">
                            <input v-model="formdata.country" type="text" class="form-control" placeholder="E.g. US" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data: () => ({
        formdata: {
            accountId: undefined,
            language: 'en',
            country: undefined
        },
        languages: [
            { id: 'en', text: 'English' },
            { id: 'de', text: 'Germany' },
            { id: 'fr', text: 'France' },
            { id: 'jp', text: 'Japan' },
        ],
        processing: false
    }),
    created() {
        this.processing = true
        this.$store.dispatch('authinfo').catch(error => {
                this.$store.commit('setAlert', {
                    'color': 'danger', 
                    'message': error.response.data.message
                })
            }).finally(() => {
            this.processing = false
        })
    },
    computed: {
        ...mapState({
            accounts: state => state.google_merchant.accounts
        })
    }
}
</script>