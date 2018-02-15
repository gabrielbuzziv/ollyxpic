export default {
    getPlayer (name) {
        return window.axios.get(`players/${name}`)
    },

    getPlayerSkills (id) {
        return window.axios.get(`players/${id}/skills`)
    },

    getPlayerExperience (id) {
        return window.axios.get(`players/${id}/experience`)
    }
}