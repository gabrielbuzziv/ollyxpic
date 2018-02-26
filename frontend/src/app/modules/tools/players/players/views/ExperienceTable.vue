<template>
    <panel class="advances">
        <div class="alert alert-warning">
            <p>
                All data is tracked from <a href="http://tibia.com" target="_blank">tibia.com</a>.
            </p>
        </div>

        <el-tabs v-model="tabs" @tab-click="loadTab">
            <!--<el-tab-pane label="Overview" name="overview">-->
                <!--asda-->
            <!--</el-tab-pane>-->

            <el-tab-pane :label="month.label" :name="`${index}`" :key="index" v-for="month, index in months">
                <page-load class="no-padding" :loading="loading">
                    <div v-if="experience.length">
                        <month-cards :experience="experience" :month="month" />

                        <table class="table advances" v-if="! loading">
                            <tr v-for="advance in experience">
                                <td width="130">
                                    <b>{{ advance.updated_at | date }}</b>
                                    <span>{{ advance.updated_at | dateForHuman }}</span>
                                </td>
                                <td class="level">
                                    <span>Level</span>
                                    <b>{{ advance.level }}</b>

                                    <el-progress :percentage="getExperienceBar(advance).percentage" :show-text="false"/>
                                </td>
                                <td>
                                    <b>{{ advance.experience.format() }}</b>
                                    <span>Experience</span>
                                </td>
                                <td class="advance">
                                    <div v-if="advance.advance >= 0">
                                        <b>+{{ advance.advance.format() }}</b>
                                        <span>+ Experience</span>
                                    </div>

                                    <div v-else>
                                        <b class="loose">{{ advance.advance.format() }}</b>
                                        <span>+ Experience</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div v-else>
                        <div class="alert alert-warning margin-bottom-0">
                            <p>No experience records found, we only track players from highscores players.</p>
                        </div>
                    </div>
                </page-load>
            </el-tab-pane>
        </el-tabs>
    </panel>
</template>

<script>
    import MonthCards from './experience/MonthCards'

    import services from '../services'

    export default {
        components: { MonthCards },

        data () {
            return {
                tabs: 0,
                loading: false
            }
        },

        computed: {
            player () {
                return this.$store.getters['player/GET_PLAYER']
            },

            months () {
                return this.$store.getters['player/GET_MONTHS']
            },

            experience () {
                return this.$store.getters['player/GET_EXPERIENCE']
            },

            monthExperience () {
                const firstOfMonth = this.experience[this.experience.length - 1]
                const lastOfMonth = this.experience[0]
                return this.experience.length ? lastOfMonth.experience - firstOfMonth.experience: 0
            },

            monthLevels () {
                const firstOfMonth = this.experience[this.experience.length - 1]
                const lastOfMonth = this.experience[0]
                return this.experience.length ? lastOfMonth.level - firstOfMonth.level : 0
            }
        },

        filters: {
            date (date) {
                return moment(date).format('DD MMM YYYY')
            },

            dateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'America/New_York').fromNow()
            }
        },

        methods: {
            loadTab (tab) {
                if (tab.name == 'overview') {
                    return false
                }

                this.loadExperience(tab.name)
            },

            loadExperience (index) {
                const id = this.player.id
                const month = this.months[index].date

                this.loading = true
                this.$store.dispatch('player/FETCH_EXPERIENCE', { id, month })
                    .then(() => this.loading = false)
                    .catch(() => this.loading = false)
            },

            getExperienceBar (advance) {
                const nextLevel = advance.level + 1
                const nextLevelExp = ((50 * Math.pow((nextLevel - 1), 3)) - (150 * Math.pow((nextLevel - 1), 2)) + (400 * (nextLevel - 1))) / 3
                const currentExp = advance.experience
                const expToNextLevel = 50 * Math.pow(advance.level, 2) - 150 * advance.level + 200
                const expLeft = nextLevelExp - currentExp
                const currentLeveledExp = expToNextLevel - expLeft
                const percentage = parseInt((currentLeveledExp * 100) / expToNextLevel)

                return this.experience
                    ? { leftExperience: expLeft, percentage: percentage }
                    : { leftExperience: 0, percentage: 0 }
            },
        },
    }
</script>
