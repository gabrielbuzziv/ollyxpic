export default {
    getMonths () {
        return window.axios.get(`players/experience/months`)
    },

    getPlayer (name) {
        return window.axios.get(`players/${name}`)
    },

    getPlayerLevel (name) {
        return window.axios.get(`players/${name}/level`)
    },

    getPlayerSkills (name) {
        return window.axios.get(`players/${name}/skills`)
    },

    getPlayerExperience (name, month) {
        return window.axios.get(`players/${name}/experience`, { params: { month } })
    },

    getPlayerOverview (name) {
        return window.axios.get(`players/${name}/overview`)
    }
}