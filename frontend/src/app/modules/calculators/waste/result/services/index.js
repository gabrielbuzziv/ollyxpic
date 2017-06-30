export default {
    find (id) {
        return window.axios.get(`waste/${id}`)
    },
}