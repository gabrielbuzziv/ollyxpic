export default {
    fetchWorlds () {
        return window.axios.get(`worlds`)
    }
}