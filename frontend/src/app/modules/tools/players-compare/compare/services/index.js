export default {
    getPlayer (name) {
        return window.axios.get(`/players/${name}/compare`)
    },

    getHighscores (limit) {
        return window.axios.get(`/highscores`, { params: { limit } })
    }
}