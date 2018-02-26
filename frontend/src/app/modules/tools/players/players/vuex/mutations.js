export default {
    'player/PLAYER' (state, data) {
        state.player = data
    },

    'player/LEVEL' (state, data) {
        state.level = data
    },

    'player/SKILLS' (state, data) {
        state.skills = data
    },

    'player/MONTHS' (state, data) {
        state.months = data
    },

    'player/EXPERIENCE' (state, data) {
        state.experience = data
    }
}