<template>
    <page-load id="warzone">
        <page-title>
            <img :src="image_path_by_name('item', 'medal of honour')" alt="">
            Warzone
            <span>MVP's</span>
        </page-title>

        <div class="row stats" v-if="players.length">
            <div class="col-md-4">
                <panel>
                    <div class="icon">
                        <img :src="image_path_by_name('item', 'medal of honour')" class="margin-right-10">
                    </div>

                    <div class="info">
                        <strong>Fragger</strong>
                        <span>{{ players[mvps.damage].player }}</span>
                    </div>
                </panel>
            </div>

            <div class="col-md-4">
                <panel>
                    <div class="icon">
                        <img :src="image_path_by_name('item', 'medal of honour')" class="margin-right-10">
                    </div>

                    <div class="info">
                        <strong>Best Hit</strong>
                        <span>{{ players[mvps.besthit].player }}</span>
                    </div>
                </panel>
            </div>

            <div class="col-md-4">
                <panel>
                    <div class="icon">
                        <img :src="image_path_by_name('item', 'medal of honour')" class="margin-right-10">
                    </div>

                    <div class="info">
                        <strong>Best Experience</strong>
                        <span>{{ players[mvps.experience].player }}</span>
                    </div>
                </panel>
            </div>
        </div>

        <div class="row stats">
            <div class="col-md-4">
                <panel>
                    <div class="icon"></div>

                    <div class="info">
                        <strong>Boss</strong>
                        <span>{{ result.boss }}</span>
                    </div>
                </panel>
            </div>

            <div class="col-md-4">
                <panel>
                    <div class="icon"></div>

                    <div class="info">
                        <strong>Players</strong>
                        <span>{{ players.length }}</span>
                    </div>
                </panel>
            </div>

            <div class="col-md-4">
                <panel>
                    <div class="icon"></div>

                    <div class="info">
                        <strong>Data Accuracy</strong>
                        <span>{{ participation }}% / 100%</span>
                    </div>
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <panel>
                    <table class="table margin-bottom-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Player</th>
                                <th class="text-center">Damage</th>
                                <th class="text-center">Best Hit</th>
                                <th class="text-center">Experience</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="player, index in players">
                                <td width="30">{{ index + 1 }}</td>
                                <td>
                                    <a :href="`https://secure.tibia.com/community/?subtopic=characters&name=${player.player}`"
                                       target="_blank">
                                        {{ player.player }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{ player.damage }}
                                    <small>({{ player.participation }} %)</small>
                                </td>
                                <td class="text-center">
                                    {{ player.besthit }}
                                </td>
                                <td class="text-center">
                                    <span v-if="player.experience > 0">{{ player.experience }}</span>
                                    <span v-else>¯\_(ツ)_/¯</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                result: []
            }
        },

        computed: {
            players () {
                if (this.result && this.result.players && this.result.players.length) {
                    return this.result.players.sort((a, b) => {
                        return parseInt(b.damage) - parseInt(a.damage)
                    })
                }

                return [];
            },

            mvps () {
                if (this.result && this.result.players && this.result.players.length) {
                    const experience     = this.result.players.map(player => parseInt(player.experience))
                    const bestExperience = this.result.players.reduce((carry, player) => {
                        const exp = parseInt(player.experience)
                        return exp > carry ? exp : carry
                    }, 0)

                    const hit  = this.result.players.map(player => parseInt(player.besthit))
                    const bestHit = this.result.players.reduce((carry, player) => {
                        const hit = parseInt(player.besthit)
                        return hit > carry ? hit : carry
                    }, 0)

                    const damage  = this.result.players.map(player => parseInt(player.damage))
                    const bestDamage = this.result.players.reduce((carry, player) => {
                        const damage = parseInt(player.damage)
                        return damage > carry ? damage : carry
                    }, 0)


                    return {
                        experience: experience.indexOf(bestExperience),
                        besthit: hit.indexOf(bestHit),
                        damage: damage.indexOf(bestDamage),
                    }
                }

                return [];
            },

            participation () {
                if (this.result && this.result.players && this.result.players.length) {
                    return this.players.reduce((carry, player) => {
                        return carry + player.participation
                    }, 0).formatMoney(2, '.', '.')
                }

                return 0
            }
        },

        mounted () {
            services.fetchMVP(this.$route.params.id)
                    .then(response => {
                        this.result = response.data
                    })
        }
    }
</script>