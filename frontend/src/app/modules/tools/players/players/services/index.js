export default {
    getPlayer (name) {
        return window.axios.get(`players/${name}`)
    },
}