export default {
    'player/GET_PLAYER' ({ player }) {
        return player
    },

    'player/GET_LEVEL' ({ level }) {
        return level
    },

    'player/GET_SKILLS' ({ skills }) {
        return skills
    },

    'player/GET_MONTHS' ({ months }) {
        return months.map(month => ({
            label: moment(month).format('MMM YYYY'),
            date: month
        }))
    },

    'player/GET_EXPERIENCE' ({ experience }) {
        return experience
    }
}