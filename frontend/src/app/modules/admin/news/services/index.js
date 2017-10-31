export default {
    fetchPosts () {
        return window.axios.get(`admin/posts`)
    },

    save (action, data) {
        return window.axios.post(action, data)
    },

    find (id) {
        return window.axios.get(`admin/posts/${id}`)
    },

    remove (id) {
        return window.axios.post(`admin/posts/${id}`, { _method: 'DELETE' })
    }
}