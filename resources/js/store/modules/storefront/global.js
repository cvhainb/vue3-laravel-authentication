import apiGlobal from '@/api/storefront/global'

// initial state
const state = {
    alert: {}
}

// getters
const getters = {
    translation: () => (item, field, locale) => {
        if(!_.isEmpty(item.translations)) {
            return item.translations.find(trans => trans.locale === locale)[field] || {}
        }
    },
}

// actions 
const actions = {
    
}

// mutations is often used to filter results
const mutations = {
    setAlert(state, response) {
        state.alert = { ...response, time: new Date().getTime() }
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}