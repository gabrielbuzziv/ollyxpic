export default {
    fetchTranslations () {
        return window.axios.get(`admin/translations`)
    },

    add  (data) {
        return window.axios.post(`admin/translations`, data)
    },

    save (id, data) {
        return window.axios.post(`admin/translations/${id}`, data)
    }
}