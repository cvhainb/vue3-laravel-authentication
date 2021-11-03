import apiProduct from '@/api/storefront/product'

// initial state
const state = {
    products: []
}

// getters
const getters = {
    
}

// actions 
const actions = {
    async allProducts({ commit }) {
        commit('setProducts', await apiProduct.allProducts())
    },
}

// mutations is often used to filter results
const mutations = {
    setProducts(state, response) {
        state.products = response.data.products
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}