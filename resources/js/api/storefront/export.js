export default {

    generateDataFeed(formdata) {
        return axios.post(`/api/v1/storefront/export/datafeed`, formdata, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_user')}`
            }
        })
    },
}