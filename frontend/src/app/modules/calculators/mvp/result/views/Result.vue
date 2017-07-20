<template>
    <page-load id="blessings">
        <page-title>
            <img :src="image_path('item', 862)" alt="">
            Boss
            <span>MVP</span>
        </page-title>

        <panel>
            <table class="table margin-bottom-0">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th class="text-center">Experience</th>
                        <th class="text-center">Best Hit</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="player, index in players">
                        <td>
                            <a :href="`https://secure.tibia.com/community/?subtopic=characters&name=${player.player}`"
                               target="_blank">
                                {{ player.player }}
                            </a>
                        </td>
                        <td class="text-center">
                            <el-tooltip content="Best Experience" v-if="mvps.experience == index">
                                <img :src="image_path('item', 862)" class="margin-right-10">
                            </el-tooltip>
                            {{ player.experience }}
                        </td>
                        <td class="text-center">
                            <el-tooltip content="Best Hit" v-if="mvps.besthit == index">
                                <img :src="image_path('item', 862)" class="margin-right-10">
                            </el-tooltip>
                            {{ player.besthit }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </panel>
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
                        return parseInt(b.experience) - parseInt(a.experience)
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

                    const damage  = this.result.players.map(player => parseInt(player.besthit))
                    const bestHit = this.result.players.reduce((carry, player) => {
                        const hit = parseInt(player.besthit)
                        return hit > carry ? hit : carry
                    }, 0)


                    return {
                        experience: experience.indexOf(bestExperience),
                        besthit: damage.indexOf(bestHit),
                    }
                }

                return [];
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