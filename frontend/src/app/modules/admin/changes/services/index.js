export default {
    fetchChanges () {
        return window.axios.get(`changes`)
    },

    save (action, data) {
        return window.axios.post(action, data)
    },

    find (id) {
        return window.axios.get(`changes/${id}`)
    },

    remove (id) {
        return window.axios.post(`changes/${id}`, { _method: 'DELETE' })
    }
}