export default {
    getCategories () {
        return window.axios.get(`categories`)
    },

    getItemsByCategory (categories) {
        return window.axios.get(`admin/damage-protection/items`, { params: { categories: categories } })
    },

    toggleItemUsable (item_id, category_id) {
        return window.axios.post(`admin/damage-protection/toggle/${item_id}/${category_id}`)
    },
}