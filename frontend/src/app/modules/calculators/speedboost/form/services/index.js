export default {
    fetchTiles () {
        return window.axios.get(`tiles`)
    },

    updateFriction (id, params) {
        return window.axios.post(`tiles/${id}/friction`, params)
    }
}