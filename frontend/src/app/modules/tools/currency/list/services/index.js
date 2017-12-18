export default {
    fetchWorlds (sort,  filters) {
        return window.axios.get(`worlds`, { params: { sort, filters } })
    },

    getLastCurrencies (id) {
        return window.axios.get(`worlds/${id}/currencies`)
    },

    find (id) {
        return window.axios.get(`worlds/${id}`)
    }
}