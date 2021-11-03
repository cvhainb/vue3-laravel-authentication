export default {

    getUserProfile() {
        return axios.get('/api/v1/storefront/user/profile', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        })
    },
    updateUserProfile(formdata) {
        return axios.post('/api/v1/storefront/user/update-profile', formdata, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        })
    },
}