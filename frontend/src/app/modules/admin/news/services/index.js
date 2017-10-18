export default {
    fetchPosts () {
        return window.axios.get(`posts`)
    },

    save (action, data) {
        return window.axios.post(action, data)
    },

    find (id) {
        return window.axios.get(`posts/${id}`)
    },

    remove (id) {
        return window.axios.post(`posts/${id}`, { _method: 'DELETE' })
    }
}