<template>
    <panel class="player">
        <page-load class="no-padding" :loading="loading">
            <header>
                <button class="btn-remove" @click.prevent="removeFromCompare()" v-if="index != 1">
                    <i class="mdi mdi-close"></i>
                </button>

                <h3>{{ details.name }}</h3>
                <span class="vocation">{{ details.vocation }}</span>
            </header>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-view-list margin-right-5"></i>
                        Overview
                    </h4>
                </li>

                <li>
                    <b>{{ details.level }}</b>
                    <span>Level</span>
                </li>

                <li>
                    <b>{{ hitpoints.format() }}</b>
                    <span>Hitpoints</span>
                </li>

                <li>
                    <b>{{ manapoints.format() }}</b>
                    <span>Manapoints</span>
                </li>

                <li>
                    <b>{{ capacity.format() }}</b>
                    <span>Capacity</span>
                </li>

                <li>
                    <b>{{ speed.format() }}</b>
                    <span>Speed</span>
                </li>
            </ul>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-sword margin-right-5"></i>
                        Skills
                    </h4>
                </li>

                <li>
                    <b>{{ magicLevel.level || '~' }}</b>
                    <span>Magic Level</span>
                </li>

                <li v-if="isPaladin">
                    <b><b>{{ distance.level || '~' }}</b></b>
                    <span>Distance Fighting</span>
                </li>

                <li v-else>
                    <b><b>{{ meleeSkill.level || '~' }}</b></b>
                    <span>{{ meleeSkill.skill }} Fighting</span>
                </li>

                <li>
                    <b>{{ shielding.level || '~' }}</b>
                    <span>Shielding</span>
                </li>
            </ul>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-trending-up margin-right-5"></i>
                        Experience
                    </h4>
                </li>

                <li>
                    <b>{{ getExperienceAdvances(lastMonth) | plus }}</b>
                    <span>Last Month Experience</span>
                </li>

                <li>
                    <b>{{ getExperienceAdvances(currentMonth) | plus }}</b>
                    <span>Month Experience</span>
                </li>

                <li>
                    <b>{{ getAverageDailyExperience(currentMonth) | plus }}</b>
                    <span>AVG Daily Experience</span>
                </li>

                <li>
                    <b>{{ getLevelAdvances(lastMonth) | plus }}</b>
                    <span>Last Month Levels</span>
                </li>

                <li>
                    <b>{{ getLevelAdvances(currentMonth) | plus }}</b>
                    <span>Month Levels</span>
                </li>
            </ul>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-wunderlist margin-right-5"></i>
                        Misc
                    </h4>
                </li>

                <li>
                    <b>{{ deaths }}</b>
                    <span>Month Deaths</span>
                </li>

                <li>
                    <b>{{ details.achievements }}</b>
                    <span>Achievements Points</span>
                </li>

                <li>
                    <b>{{ loyaltyPercentage || '~' }}</b>
                    <span>Loyalty</span>
                </li>
            </ul>
        </page-load>
    </panel>
</template>

<script>
    import services from '../services'

    export default {
        props: ['name', 'index'],

        data () {
            return {
                player: {},
                loading: true,
            }
        },

        computed: {
            details () {
                return this.player && this.player.details ? this.player.details : {}
            },

            skills () {
                return this.player && this.player.skills ? this.player.skills : []
            },

            experience () {
                return this.player && this.player.experience ? this.player.experience : []
            },

            hitpoints () {
                if (! this.details) return 0

                switch (this.details.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return (this.details.level - 8) * 15 + 185
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.details.level - 8) * 10 + 185
                    default:
                        return this.details.level * 5 + 145
                }
            },

            manapoints () {
                switch (this.details.vocation) {
                    case 'Druid':
                    case 'Sorcerer':
                    case 'Elder Druid':
                    case 'Master Sorcerer':
                        return (this.details.level - 8) * 30 + 90
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.details.level - 8) * 15 + 90
                    default:
                        return (this.details.level - 8) * 5 + 50
                }
            },

            speed () {
                return this.details.level + 109
            },

            capacity () {
                switch (this.details.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return (this.details.level - 8) * 25 + 470
                    case 'Paladin':
                    case 'Royal Paladin':
                        return (this.details.level - 8) * 20 + 470
                    default:
                        return (this.details.level - 8) * 10 + 400
                }
            },

            loyalty () {
                const magicLevel = this.skills.filter(skill => skill.skill == 'loyalty')[0]
                return magicLevel ? magicLevel : { level: 0, skill: 'Loyalty' }
            },

            magicLevel () {
                const magicLevel = this.skills.filter(skill => skill.skill == 'magic')[0]
                return magicLevel ? magicLevel : { level: 0, skill: 'Magic Level' }
            },

            shielding () {
                const magicLevel = this.skills.filter(skill => skill.skill == 'shielding')[0]
                return magicLevel ? magicLevel : { level: 0, skill: 'Shielding' }
            },

            distance () {
                const magicLevel = this.skills.filter(skill => skill.skill == 'distance')[0]
                return magicLevel ? magicLevel : { level: 0, skill: 'Distance Figthing' }
            },

            meleeSkill () {
                const melee = this.skills.filter(skill => skill.skill == 'axe' || skill.skill == 'club' || skill.skill == 'sword')
                    .sort((a, b) => b.level - a.level)

                return melee.length ? melee[0] : { level: 0, skill: 'Melee' }
            },

            isPaladin () {
                return this.details.vocation == 'Paladin' || this.details.vocation == 'Royal Paladin'
            },

            isKnight () {
                return this.details.vocation == 'Knight' || this.details.vocation == 'Elite Knight'
            },

            lastMonth () {
                if (! this.player || ! this.player.experience || ! this.player.experience.last) return []
                const experience = this.player.experience.last

                return experience.map((exp, index) => {
                    const advance = index ? parseInt(exp.experience - experience[index - 1].experience) : 0
                    const up = index ? parseInt(exp.level - experience[index - 1].level) : 0
                    return { ...exp, advance, up }
                }).slice(1).sort((a, b) => b.id - a.id)
            },

            currentMonth () {
                if (! this.player || ! this.player.experience || ! this.player.experience.current) return []
                const experience = this.player.experience.current

                return experience.map((exp, index) => {
                    const advance = index ? parseInt(exp.experience - experience[index - 1].experience) : 0
                    const up = index ? parseInt(exp.level - experience[index - 1].level) : 0
                    return { ...exp, advance, up }
                }).slice(1).sort((a, b) => b.id - a.id)
            },

            month () {
                return this.currentMonth.length ? this.currentMonth[0].updated_at.split('-') : false
            },

            deaths () {
                if (! this.details || ! this.details.deaths) return 0
                if (! this.month) return 0

                return this.details.deaths.filter(death => {
                    const died = death.died_at.split('-')
                    return died[0] = this.month[0] && died[1] == this.month[1]
                }).length
            },

            loyaltyPercentage () {
                const percentage = this.loyalty
                    ? Math.floor((this.loyalty.level / 360)) * 5 <= 50
                        ? Math.floor((this.loyalty.level / 360)) * 5
                        : 50
                    : 0

                return `${percentage}%`
            }
        },

        watch: {
            '$route.params.first' () {
                if (this.index == 1) {
                    this.load()
                }
            },

            '$route.params.second' () {
                if (this.index == 2) {
                    this.load()
                }
            },

            '$route.params.third' () {
                if (this.index == 3) {
                    this.load()
                }
            },

            '$route.params.fourth' () {
                if (this.index == 4) {
                    this.load()
                }
            }
        },

        filters: {
            plus (value) {
                return value >= 0 ? `+ ${value.format()}` : value.format()
            },
        },

        methods: {
            load () {
                this.loading = true
                services.getPlayer(this.name)
                    .then(response => {
                        this.player = response.data
                        this.loading = false
                    })
                    .catch(() => this.loading = false)
            },

            getExperienceAdvances (month) {
                const firstOfMonth = month[month.length - 1]
                const lastOfMonth = month[0]
                return month.length ? lastOfMonth.experience - firstOfMonth.experience : 0
            },

            getAverageDailyExperience (month) {
                const total = month.reduce((carry, experience) => carry + parseInt(experience.advance), 0)
                return total / month.length
            },

            getLevelAdvances (month) {
                const firstOfMonth = month[month.length - 1]
                const lastOfMonth = month[0]
                return month.length ? lastOfMonth.level - firstOfMonth.level : 0
            },

            removeFromCompare () {
                let players = Object.values(this.$route.params).filter(player => player != null)

                if (players.length == 1) {
                    this.$message.error('You need to have at least one player in the compare list.')
                    return false
                }

                players = players.filter(player => player != this.name)

                let params = {}

                switch (players.length) {
                    case 3:
                        params = { first: players[0], second: players[1], third: players[2] }
                        break
                    case 2:
                        params = { first: players[0], second: players[1] }
                        break
                    case 1:
                        params = { first: players[0] }
                        break
                }

                this.$router.push({ name: 'compare.players', params: params })
            }
        },

        mounted () {
            this.load()
        }
    }
</script>