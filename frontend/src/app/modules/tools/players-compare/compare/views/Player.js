export default {
    getVocation (vocation) {
        switch (vocation) {
            case 'Knight':
            case 'Elite Knight':
                return 'Knight'
            case 'Paladin':
            case 'Royal Paladin':
                return 'Paladin'
            case 'Sorcerer':
            case 'Master Sorcerer':
                return 'Sorcerer'
            case 'Druid':
            case 'Elder Druid':
                return 'Druid'
            default:
                return 'None'
        }
    },

    getHitpoints (level, vocation) {
        switch (this.getVocation(vocation)) {
            case 'Knight':
                return (level - 8) * 15 + 185
            case 'Paladin':
                return (level - 8) * 10 + 185
            default:
                return level * 5 + 145
        }
    },

    getManapoints (level, vocation) {
        switch (this.getVocation(vocation)) {
            case 'Druid':
            case 'Sorcerer':
                return (level - 8) * 30 + 90
            case 'Paladin':
                return (level - 8) * 15 + 90
            default:
                return (level - 8) * 5 + 50
        }
    },

    getCapacity (level, vocation) {
        switch (this.getVocation(vocation)) {
            case 'Knight':
                return (level - 8) * 25 + 470
            case 'Paladin':
                return (level - 8) * 20 + 470
            default:
                return (level - 8) * 10 + 400
        }
    },

    getSpeed (level) {
        return level + 109
    },

    getSkill (skills, type) {
        if (type == 'melee') {
            const melee = skills.filter(skill => skill.skill == 'axe' || skill.skill == 'club' || skill.skill == 'sword')
                .sort((a, b) => b.level - a.level)

            return melee.length ? melee[0] : { level: 0, skill: 'Melee' }
        }

        const skillLevel = skills.filter(skill => skill.skill == type)[0]
        return skillLevel ? skillLevel : { level: 0, skill: type }
    },

    getMonthExperience (month) {
        const experience = month

        return experience.map((exp, index) => {
            const advance = index ? parseInt(exp.experience - experience[index - 1].experience) : 0
            const up = index ? parseInt(exp.level - experience[index - 1].level) : 0
            return { ...exp, advance, up }
        }).slice(1).sort((a, b) => b.id - a.id)
    }
}