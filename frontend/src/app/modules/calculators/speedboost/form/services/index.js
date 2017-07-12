export default {
    fetchTiles () {
        return window.axios.get(`tiles`)
    }
}