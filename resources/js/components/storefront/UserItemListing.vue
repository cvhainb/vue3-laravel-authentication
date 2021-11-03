<template>
    <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 px-3">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="mb-0">Items</h4>
                    <div>
                        <span v-if="start === true" class="me-2">{{ processing }}%</span>
                        <button :disabled="start === true" class="btn btn-success" @click="syncProducts">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                            </svg>
                            Sync data
                        </button>
                    </div>
                </div>
                <div class="p-3 bg-dark text-white">
                        <div class="input-group">
                            <input v-model="keyword" type="search" class="form-control rounded-0" placeholder="Find your product by name, SKU, GTIN,..">
                            <button class="btn dropdown-toggle text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371h-1.781zm1.57-.785L11 2.687h-.047l-.652 2.157h1.351z"/>
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645V14zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293V2.5z"/>
                                </svg>
                                Sort
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="" class="dropdown-item">Sort here</a>
                                </li>
                                <!-- <li v-for="(sort, index) in filters.sort" :key="index">
                                    <router-link class="dropdown-item" :to="{ path: `/admin/appearance/article/all`, query: Object.assign({}, urlGetAllParams(['sort', 'page']), { sort: sort.id } ) }">{{sort.text}}</router-link>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                <div class="card card-body shadow-sm border-top-0">
                    <template v-if="loading === true">
                        <div class="d-flex justify-content-center p-3">
                            <span class="spinner-grow spinner-grow-sm mx-1 text-info" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm mx-1 text-info" role="status" aria-hidden="true"></span>
                            <span class="spinner-grow spinner-grow-sm mx-1 text-info" role="status" aria-hidden="true"></span>
                        </div>
                    </template>
                    <template v-else>
                        <p v-if="products && products.length === 0" class="text-danger text-center mb-0">You need to load your products into our system by clicking on the Sync data button above. Once your products are in the system, you can use the features to increase sales for your store.</p>
                        <div v-else>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Store's ID</th>
                                    <th class="text-center">Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th class="text-center">Store's status</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" :key="product.id">
                                        <td>{{ product.store_product_id }}</td>
                                        <td class="text-center">
                                            <template v-if="product.images[0]">
                                                <img :src="product.images[0]" width="60" alt="" class="img-thumbnail">
                                            </template>
                                            <template v-else>
                                                <img src="/assets/img/no-image.png" alt="" class="img-thumbnail">
                                            </template>
                                        </td>
                                        <td>{{ translation(product, 'name', 'en') }}</td>
                                        <td>{{ product.sku }}</td>
                                        <td>{{ (+product.price).toFixed(2) }} USD</td>
                                        <td class="text-center">
                                            <svg v-if="+product.status === 1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-circle-fill text-danger" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                            </svg>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
export default {
    data: () => ({
        keyword: undefined,
        start: false,
        processing: 0,
        page: 1,
        loading: false
    }),
    created() {
        this.loading = true
        this.$store.dispatch('allProducts').then(() => {
            // 
        }).finally(() => {
            this.loading = false
        })
    },
    methods: {
        syncProducts() {

            this.start = true, this.loading = true

            this.$store.dispatch('syncProducts', { page: this.page }).then(() => {
                
                if(this.syncing.current < this.syncing.total) {
                    this.processing = Math.round(this.syncing.current*100/this.syncing.total)
                    this.page++
                    this.syncProducts()
                } else {
                    this.processing = 0, this.start = false, this.page = 1
                    this.$store.dispatch('allProducts').then(() => {
                        this.$store.commit('setAlert', {
                            'color': 'success', 
                            'message': 'Synchronization is complete.'
                        })
                    }).finally(() => {
                        this.loading = false
                    })
                }

            }).catch(error => {
                this.start = false, this.loading = false
                this.$store.commit('setAlert', {
                    'color': 'danger', 
                    'message': error.response.data.message
                })
            })
        },
    },
    computed: {
        ...mapGetters(['translation']),
        ...mapState({
            syncing: state => state.sync.syncing,
            products: state => state.product.products,
        })
    }
}
</script>