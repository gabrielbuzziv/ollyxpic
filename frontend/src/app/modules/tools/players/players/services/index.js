export default {
    getMonths () {
        return window.axios.get(`players/experience/months`)
    },

    getPlayer (name) {
        return window.axios.get(`players/${name}`)
    },

    getPlayerLevel (id) {
        return window.axios.get(`players/${id}/level`)
    },

    getPlayerSkills (id) {
        return window.axios.get(`players/${id}/skills`)
    },

    getPlayerExperience (id, month) {
        return window.axios.get(`players/${id}/experience`, { params: { month } })
    }
}