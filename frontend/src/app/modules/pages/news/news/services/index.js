export default {
    getPost (slug) {
        return window.axios.get(`news/show`, { params: { slug }})
    },

    getHotnews () {
        return window.axios.get(`news/hot`)
    }
}