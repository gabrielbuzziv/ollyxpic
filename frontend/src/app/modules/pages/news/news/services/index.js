export default {
    getPost (id) {
        return window.axios.get(`news`, { params: { id } })
    },

    fetchNews () {
        return window.axios.get(`news/list`)
    }
}