export default {
    save (data) {
        return window.axios.post('hunting-spots')
    },

    getVocations () {
        return window.axios.get('vocations')
    },

    getSupplies (category) {
        return window.axios.get('supplies', { params: { category }})
    },

    getCreatures (query) {
        return window.axios.get('creatures', { params: { query } })
    },
}