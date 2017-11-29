export default {
    searchItem (item) {
        const params = {
            query: item
        }

        return window.axios.get(`items/search`, { params })
    },
}