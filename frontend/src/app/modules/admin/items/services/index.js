export default {
    getItemsFromCategory (id) {
        return window.axios.get(`admin/items/${id}`)
    },

    getCategories () {
        return window.axios.get(`admin/categories`)
    },

    toggleUsable (id) {
        return window.axios.get(`admin/items/${id}/usable`)
    },

    toggleSupply (id) {
        return window.axios.get(`admin/items/${id}/supply`)
    },

    toggleEquipment (id) {
        return window.axios.get(`admin/items/${id}/equipment`)
    },

    remove (id) {
        return window.axios.post(`admin/items/${id}`, { _method: 'delete' })
    }
}