export default {
    getHighscores () {
        return window.axios.get(`highscores`)
    },

    getPlayerDetails (name) {
        return window.axios.get(`highscores/${name}`)
    },

    getPlayerAdvances (name, type) {
        return window.axios.get(`highscores/${name}/${type}`)
    }
}