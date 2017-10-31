export default {
    getCategories () {
        return window.axios.get(`admin/categories`)
    },

    toggleUsable (id) {
        return window.axios.get(`admin/categories/${id}/usable`)
    },

    find (id) {
        return window.axios.get(`admin/categories/${id}/show`)
    },

    save (id, data) {
        return window.axios.post(`admin/categories/${id}`, data)
    }
}