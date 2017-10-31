export default {
    getCreatures (query) {
        return window.axios.get('creatures', { params: { query } })
    },

    getCreature (id) {
        return window.axios.get(`creatures/${id}`)
    }
}