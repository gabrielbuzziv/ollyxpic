export default {
    getCategories () {
        return window.axios.get('categories')
    },

    getItems (id) {
        return window.axios.get(`items/${id}`)
    }
}