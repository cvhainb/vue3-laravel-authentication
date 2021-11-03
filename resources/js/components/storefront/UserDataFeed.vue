<template>
    <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-12 px-3">
                <h4 class="mb-3">Data Feed Export</h4>
                <div class="card card-body shadow-sm">
                    <form @submit.prevent="generateDataFeed">
                        <div class="card-title h5 mb-3">Manual Export</div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-12">
                                <label class="form-label">Feed type</label>
                            </div>
                            <div class="col-md-9 col-12">
                                <select v-model="formdata.feed_type" class="form-select">
                                    <option v-for="item in feedTypes" :key="item.id" :value="item.id">{{ item.text }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-12">
                                <label class="form-label">Measurement unit</label>
                            </div>
                            <div class="col-md-9 col-12">
                                <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                        <label class="d-inline-block">Weight:</label>
                                        <select v-model="formdata.weight_unit" class="form-select d-inline-block w-auto ms-3">
                                            <option v-for="item in weightUnits" :key="item.id" :value="item.id">{{ item.text }}</option>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center me-5">
                                        <label class="d-inline-block">Dimension:</label>
                                        <select v-model="formdata.dimension_unit" class="form-select d-inline-block w-auto ms-3">
                                            <option v-for="item in dimensionUnits" :key="item.id" :value="item.id">{{ item.text }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-12">
                                <label class="form-label">Ships from country</label>
                            </div>
                            <div class="col-md-9 col-12">
                                <input v-model="formdata.ships_from_country" type="text" class="form-control" placeholder="E.g. US">
                            </div>
                        </div>
                        <component :is="formdata.feed_type" @updateContent="updateContent"></component>
                        <div class="text-end">
                            <button :disabled="processing === true" class="btn btn-success">
                                <div v-if="processing === true" class="spinner-grow spinner-grow-sm text-info" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z"/>
                                    <path fill-rule="evenodd" d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z"/>
                                </svg>
                                Export now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Google from '@/components/storefront/templates/feedtype/Google'
export default {
    data: () => ({
        formdata: {
            feed_type: 'Google',
            ships_from_country: undefined,
            weight_unit: 'lb',
            dimension_unit: 'in'
        },
        feedTypes: [
            { id: 'Google', text: 'Google Shopping' },
            { id: 'Facebook', text: 'Facebook Catalog' },
            { id: 'Bonanza', text: 'Bonanza' },
        ],
        dimensionUnits: [
            { id: 'in', text: 'in' },
            { id: 'cm', text: 'cm' },
        ],
        weightUnits: [
            { id: 'lb', text: 'lb' },
            { id: 'oz', text: 'oz' },
            { id: 'g', text: 'g' },
            { id: 'kg', text: 'kg' },
        ],
        processing: false
    }),
    components: { Google },
    methods: {
        generateDataFeed() {
            this.processing = true
            this.$store.dispatch('generateDataFeed', this.formdata).finally(() => {
                this.processing = false
            })
        },
        updateContent(v) {
            this.formdata = { ...this.formdata, ...v }
        }
    }
}
</script>