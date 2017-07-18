export default {
    searchItem (type) {
        const params = {
            query: type
        }

        return window.axios.get(`items/category`, { params })
    },

    saveItem (item) {
        return window.axios.post(`items/category`, item)
    },
}