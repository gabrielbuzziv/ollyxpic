export default {
    getWorlds () {
        return window.axios.get(`worlds`)
    },

    getHighscores (vocation = '', world = '', type = null, limit = 300) {
        return window.axios.get(`highscores`, { params: { vocation, world, type, limit } })
    },

    getSkillHighscores (skill, world = '') {
        return window.axios.get(`highscores/skills`, { params: { skill, world } })
    },
}