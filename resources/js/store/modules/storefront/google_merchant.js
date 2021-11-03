import apiGoogleMerchant from '@/api/storefront/google_merchant'

// initial state
const state = {
    accounts: {}
}

// getters
const getters = {}

// actions 
const actions = {
    async authinfo({ commit }) {
        commit('setAccount', await apiGoogleMerchant.authinfo())
    },
}

// mutations is often used to filter results
const mutations = {
    setAccount(state, response) {
        state.accounts = response.data.accounts
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}