<template>
    <panel class="player">
        <page-load class="no-padding" :loading="loading">
            <header>
                <button class="btn-remove" @click.prevent="removeFromCompare()">
                    <i class="mdi mdi-close"></i>
                </button>

                <router-link :to="{ name: 'players', params: { name: details.name } }">
                    <h3>{{ details.name }}</h3>
                </router-link>
                <span class="vocation">{{ details.vocation }}</span>
            </header>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-view-list margin-right-5"></i>
                        Overview
                    </h4>

                    <small>{{ details.name }}</small>
                </li>

                <li class="main" :class="{ 'highest': isHighest('level') }">
                    <b>
                        {{ details.level }}

                        <i class="mdi mdi-menu-up" v-if="isHighest('level')"></i>
                    </b>
                    <span>Level</span>
                </li>

                <li>
                    <b>{{ PlayerOptions.getHitpoints(details.level, details.vocation).format() }}</b>
                    <span>Hitpoints</span>
                </li>

                <li>
                    <b>{{ PlayerOptions.getManapoints(details.level, details.vocation).format() }}</b>
                    <span>Manapoints</span>
                </li>

                <li>
                    <b>{{ PlayerOptions.getCapacity(details.level, details.vocation).format() }}</b>
                    <span>Capacity</span>
                </li>

                <li>
                    <b>{{ PlayerOptions.getSpeed(details.level).format() }}</b>
                    <span>Speed</span>
                </li>
            </ul>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-sword margin-right-5"></i>
                        Skills
                    </h4>

                    <small>{{ details.name }}</small>
                </li>

                <li :class="{ 'highest': isHighest('magic') }">
                    <b>
                        {{ PlayerOptions.getSkill(skills, 'magic').level || '~' }}

                        <i class="mdi mdi-menu-up" v-if="isHighest('magic')"></i>
                    </b>
                    <span>Magic Level</span>
                </li>

                <li v-if="isPaladin">
                    <b>
                        {{ PlayerOptions.getSkill(skills, 'distance').level || '~' }}
                    </b>
                    <span>Distance Fighting</span>
                </li>

                <li v-else >
                    <b>
                        {{ PlayerOptions.getSkill(skills, 'melee').level || '~' }}
                    </b>
                    <span>{{ PlayerOptions.getSkill(skills, 'melee').skill }} Fighting</span>
                </li>

                <li :class="{ 'highest': isHighest('shielding') }">
                    <b>
                        {{ PlayerOptions.getSkill(skills, 'shielding').level || '~' }}

                        <i class="mdi mdi-menu-up" v-if="isHighest('shielding')"></i>
                    </b>
                    <span>Shielding</span>
                </li>
            </ul>

            <ul>
                <li class="title">
                    <h4>
                        <i class="mdi mdi-trending-up margin-right-5"></i>
                        Experience
                    </h4>

                    <small>{{ details.name }}</small>
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

                    <small>{{ details.name }}</small>
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
    import Player from './Player.js'
    import { isEmpty } from 'lodash'

    export default {
        props: ['name', 'index', 'players'],

        data () {
            return {
                player: {},
                loading: true,
            }
        },

        computed: {
            PlayerOptions () {
                return Player
            },

            comparePlayers () {
                return Object.values(this.players).filter(player => ! isEmpty(player))
            },

            details () {
                return this.player && this.player.details ? this.player.details : {}
            },

            skills () {
                return this.player && this.player.skills ? this.player.skills : []
            },

            experience () {
                return this.player && this.player.experience ? this.player.experience : []
            },

            isPaladin () {
                return this.details.vocation == 'Paladin' || this.details.vocation == 'Royal Paladin'
            },

            isKnight () {
                return this.details.vocation == 'Knight' || this.details.vocation == 'Elite Knight'
            },

            lastMonth () {
                return this.player && this.player.experience
                    ? Player.getMonthExperience(this.player.experience.last)
                    : []
            },

            currentMonth () {
                return this.player && this.player.experience
                    ? Player.getMonthExperience(this.player.experience.current)
                    : []
            },

            deaths () {
                if (! this.details || ! this.details.deaths) return 0
                const month = this.currentMonth.length ? this.currentMonth[0].updated_at.split('-') : []

                return this.details.deaths.filter(death => {
                    const died = death.died_at.split('-')
                    return month.length ? died[0] = month[0] && died[1] == month[1] : false
                }).length
            },

            loyaltyPercentage () {
                const percentage = this.loyalty
                    ? Math.floor((this.loyalty.level / 360)) * 5 <= 50
                        ? Math.floor((this.loyalty.level / 360)) * 5
                        : 50
                    : 0

                return percentage ? `${percentage}%` : 0
            }
        },

        watch: {
            '$route.params' () {
                this.load()
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
                this.$emit('update:player', {})
                services.getPlayer(this.name)
                    .then(response => {
                        this.player = response.data
                        this.$emit('update:player', this.player)
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

                this.$emit('update:player', {})
                this.$router.push({ name: 'compare.players', params: params })
            },

            isHighest (type) {
                if (! this.comparePlayers.length) return false
                let values = []
                let max = 0

                switch (type) {
                    case 'level':
                        values = this.comparePlayers.map(player => player.details[type])
                        max = values.reduce((a, b) => Math.max(a, b))
                        return this.player && this.player.details && this.player.details[type] == max ? true : false
                    case 'magic':
                    case 'distance':
                    case 'shielding':
                        values = this.comparePlayers.map(player => Player.getSkill(player.skills, type).level)
                        max = values.reduce((a, b) => Math.max(a, b))
                        return this.player && this.player.skills && Player.getSkill(this.skills, type).level == max ? true : false
                    case 'weapon':
                        values = this.comparePlayers.map(player => {
                            return Player.getVocation(player.details.vocation) == 'Knight'
                                ? Player.getSkill(player.skills, 'melee').level
                                : Player.getVocation(player.details.vocation) == 'Knight'
                                    ? Player.getSkill(player.skills, 'distance').level
                                    : 0
                        })
                        max = values.reduce((a, b) => Math.max(a, b))
                        return this.player && this.player.skills
                            ? Player.getVocation(this.player.details.vocation) == 'Knight'
                                ? Player.getSkill(this.skills, 'melee').level == max ? true : false
                                : Player.getVocation(this.player.details.vocation) == 'Paladin'
                                    ? Player.getSkill(this.skills, 'distance').level == max ? true : false
                                    : false
                            : false
                }
            }
        },

        mounted () {
            this.load()
        }
    }
</script>