export default {
    'player/GET_PLAYER' ({ player }) {
        return player
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
        return experience.map((exp, index) => {
            const advance = index ? parseInt(exp.experience - experience[index - 1].experience) : 0
            const up = index ? parseInt(exp.level - experience[index - 1].level) : 0
            return { ...exp, advance, up }
        }).slice(1).sort((a, b) => b.id - a.id)
    }
}