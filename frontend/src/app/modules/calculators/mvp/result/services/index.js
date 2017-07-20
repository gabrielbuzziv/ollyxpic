export default {
    fetchMVP (id) {
        return window.axios.get(`mvp/${id}`)
    },
}