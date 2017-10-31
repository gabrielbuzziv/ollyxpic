export default {
    fetchWorlds () {
        return window.axios.get(`worlds`)
    },

    find (id) {
        return window.axios.get(`worlds/${id}`)
    }
}