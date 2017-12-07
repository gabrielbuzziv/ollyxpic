export default {
    getCreature (query) {
        return window.axios.get('quick-looting/creatures', { params: { query } })
    },

    getCategories () {
        return window.axios.get(`quick-looting/categories`)
    },

    getItems (creature, category) {
        return window.axios.get(`quick-looting/items`, { params: { creature, category } })
    }
}