export default {
    getItemsFromCategory (id) {
        return window.axios.get(`admin/items/${id}`)
    },

    getCategories () {
        return window.axios.get(`admin/categories`)
    },

    getCategory (id) {
        return window.axios.get(`admin/category/${id}/show`)
    },

    updateProperty (id, property, value) {
        return window.axios.post(`admin/items/${id}/property`, { property, value })
    },

    toggleUsable (id) {
        return window.axios.get(`admin/items/${id}/usable`)
    },

    remove (id) {
        return window.axios.post(`admin/items/${id}`, { _method: 'delete' })
    }
}