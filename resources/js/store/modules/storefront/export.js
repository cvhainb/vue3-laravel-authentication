import apiExport from '@/api/storefront/export'

// initial state
const state = {
    feed: undefined
}

// getters
const getters = {
    
}

// actions 
const actions = {
    async generateDataFeed({ commit }, formdata) {
        commit('setFeed', await apiExport.generateDataFeed(formdata))
    },
}

// mutations is often used to filter results
const mutations = {
    setFeed(state, response) {
        state.feed = response.data.feed
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}