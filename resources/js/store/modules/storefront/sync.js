import apiSync from '@/api/storefront/sync'

// initial state
const state = {
    syncing: {
        total: 0,
        current: 0
    }
}

// getters
const getters = {
    
}

// actions 
const actions = {
    async syncProducts({ commit }, request) {
        commit('setSyncing', await apiSync.syncProducts(request))
    },
}

// mutations is often used to filter results
const mutations = {
    setSyncing(state, response) {
        state.syncing = { ...response.data }
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}