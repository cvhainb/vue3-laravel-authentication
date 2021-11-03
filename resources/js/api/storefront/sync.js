export default {

    syncProducts(request) {
        return axios.get(`/api/v1/storefront/product/syncing?page=${request.page}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        })
    },
}