import apiAuth from '@/api/storefront/auth'

// initial state
const state = {}

// getters
const getters = {
    isUserToken: () => !_.isEmpty(localStorage.getItem('jwt_user')),
}

// actions 
const actions = {
    async login({ commit }, formdata) {
        commit('setToken', await apiAuth.login(formdata))
    },
    async register({ commit }, formdata) {
        commit('setToken', await apiAuth.register(formdata))
    },

    logout({ commit }) {
        commit('setToken')
    }
}

// mutations is often used to filter results
const mutations = {
    setToken({}, response) {
        response === undefined ? localStorage.removeItem('jwt_user') : localStorage.setItem('jwt_user', response.data.token)
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}