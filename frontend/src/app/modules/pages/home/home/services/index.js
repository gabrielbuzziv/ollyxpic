export default {
    getPosts (take = 1, skip = 0) {
        return window.axios.get(`news`, { params: { take, skip } })
    },

    getHotnews () {
        return window.axios.get(`news/hot`)
    }
}