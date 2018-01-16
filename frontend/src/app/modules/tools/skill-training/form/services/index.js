export default {
    getCreature (query) {
        return window.axios.get('quick-looting/creatures', { params: { query } })
    },

    getCategories () {
        return window.axios.get(`quick-looting/categories`)
    },

    getItems (creatures, category) {
        return window.axios.get(`quick-looting/items`, { params: { creatures, category } })
    }
}