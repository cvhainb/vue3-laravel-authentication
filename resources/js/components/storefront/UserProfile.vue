<template>
    <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-12 px-3">
                <h4 class="mb-3">Profile</h4>
                <div class="card card-body shadow-sm">
                    <p v-if="msg" :class="`text-${msg.type}`">{{ msg.text }}</p>
                    <form @submit.prevent="updateProfile()">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input v-model="formdata.name" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input v-model="formdata.email" type="email" class="form-control" required disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store URL</label>
                            <input v-model="formdata.store_url" type="text" class="form-control" required disabled>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-12">
                                <label class="form-label">Password</label>
                                <input v-model="formdata.password" type="password" class="form-control" required>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label class="form-label">Confirm Password</label>
                                <input v-model="formdata.password_confirmation" type="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
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
            email: undefined,
            name: undefined,
            store_url: undefined,
            password: undefined,
            password_confirmation: undefined
        },
        msg: {
            type: undefined,
            text: undefined
        }
    }),
    created() {
        this.__assignFormData(this.profile)
    },
    methods: {
        __assignFormData(v) {
            this.formdata = { ...v }
        },
        updateProfile() {
            this.$store.dispatch('updateUserProfile', this.formdata).then(() => {
                this.msg = { type: 'success', text: 'You have just updated your profile!' }
                this.formdata.password = undefined
            }).catch(error => {
                this.msg = { type: 'danger', text: error.response.data.message }
            })
        }
    },
    computed: {
        ...mapState({
            profile: state => state.user.profile
        })
    },
    watch: {
        profile(v) {
            this.__assignFormData(v)
        }
    }
}
</script>