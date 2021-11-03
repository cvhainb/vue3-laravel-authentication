export default {
    authinfo() {
        return axios.get(`/api/v1/storefront/google/authinfo`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        })
    },
}