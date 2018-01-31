export default {
    getWorlds () {
        return window.axios.get(`worlds`)
    },

    getHighscores (vocation = '', world = '') {
        return window.axios.get(`highscores`, { params: { vocation, world } })
    },

    getSkillHighscores (skill, world = '') {
        return window.axios.get(`highscores/skills`, { params: { skill, world } })
    },
}