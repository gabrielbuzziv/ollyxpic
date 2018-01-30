export default {
    fetchPartners () {
        return window.axios.get(`admin/partners`)
    },

    save (action, data) {
        return window.axios.post(action, data)
    },

    find (id) {
        return window.axios.get(`admin/partners/${id}`)
    },

    remove (id) {
        return window.axios.post(`admin/partners/${id}`, { _method: 'DELETE' })
    }
}