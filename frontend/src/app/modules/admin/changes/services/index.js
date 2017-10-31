export default {
    fetchChanges () {
        return window.axios.get(`admin/changes`)
    },

    save (action, data) {
        return window.axios.post(action, data)
    },

    find (id) {
        return window.axios.get(`admin/changes/${id}`)
    },

    remove (id) {
        return window.axios.post(`admin/changes/${id}`, { _method: 'DELETE' })
    }
}