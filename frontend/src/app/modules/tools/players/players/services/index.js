export default {
    getPlayer (name) {
        return window.axios.get(`players/${name}`)
    },

    getCreatures (creatures) {
        return window.axios.get(`creatures/multiple`, { params: { creatures } })
    }
}