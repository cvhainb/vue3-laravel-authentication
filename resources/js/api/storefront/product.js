export default {

    allProducts() {
        return axios.get(`/api/v1/storefront/product/all`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        })
    },
}