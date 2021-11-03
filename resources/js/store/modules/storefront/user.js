import apiUser from '@/api/storefront/user'

// initial state
const state = {
    profile: {}
}

// getters
const getters = {
    
}

// actions 
const actions = {
    async getUserProfile({ commit }) {
        commit('setUserProfile', await apiUser.getUserProfile())
    },
    async updateUserProfile({ commit }, formdata) {
        commit('setUserProfile', await apiUser.updateUserProfile(formdata))
    },
}

// mutations is often used to filter results
const mutations = {
    setUserProfile(state, response) {
        state.profile = response.data.user
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}