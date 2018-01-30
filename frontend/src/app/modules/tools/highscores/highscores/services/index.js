export default {
    getHighscores (vocation = '') {
        return window.axios.get(`highscores`, { params: { vocation } })
    },

    getPlayerDetails (name) {
        return window.axios.get(`highscores/${name}`)
    },

    getPlayerAdvances (name, type) {
        return window.axios.get(`highscores/${name}/${type}`)
    }
}