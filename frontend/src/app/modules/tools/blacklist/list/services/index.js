export default {
    getCategories () {
        return window.axios.get(`categories/blacklist`)
    },

    getItems () {
        return window.axios.get(`items/blacklist`)
    }
}