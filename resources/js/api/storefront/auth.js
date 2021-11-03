export default {

    register(formdata) {
        return axios.post('/api/v1/storefront/register', formdata)
    },
    
    login(formdata) {
        return axios.post('/api/v1/storefront/login', formdata)
    },

}