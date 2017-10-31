export default {
    fetchImbuements () {
        return window.axios.get(`admin/imbuements`)
    },

    save (action, data) {
        return window.axios.post(action, data)
    },

    find (id) {
        return window.axios.get(`admin/imbuements/${id}`)
    },

    remove (id) {
        return window.axios.post(`admin/imbuements/${id}`, { _method: 'DELETE' })
    },

    getItems () {
        return window.axios.get(`admin/items/13`)
    },
}