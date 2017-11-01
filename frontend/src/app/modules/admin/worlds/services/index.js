export default {
    fetchWorlds () {
        return window.axios.get(`admin/worlds`)
    },

    find (id) {
        return window.axios.get(`admin/worlds/${id}`)
    },

    remove (id) {
        return window.axios.post(`admin/worlds/currencies/${id}`, { _method: 'DELETE' })
    },

    addCurrency (id, currency) {
        return window.axios.post(`admin/worlds/${id}/currencies`, currency)
    },

    editCurrency (id, currency) {
        const data = Object.assign(currency, { _method: 'PATCH' })
        return window.axios.post(`admin/worlds/currencies/${id}`, data)
    },
}