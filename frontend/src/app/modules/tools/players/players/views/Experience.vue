<template>
    <div v-if="! loading && ! experience.length">
        <panel>
            <div class="alert alert-warning no-data margin-bottom-0">
                <h4>Experience Statistics</h4>
                <p>Unforntunately we can't track the exp statistics from character that is not in the top 300.</p>
            </div>
        </panel>
    </div>

    <div class="experience" v-else>
        <!--<panel>-->
            <!--<page-load class="no-padding" :loading="loading">-->
                <!--<el-tabs v-model="chartTab">-->

                    <!--&lt;!&ndash; Daily Experience &ndash;&gt;-->
                    <!--<el-tab-pane label="Daily Exp" name="daily">-->
                        <!--<highcharts class="margin-top-50" id="dailyExp" :options="dailyExpChart"/>-->

                        <!--<div class="row margin-top-25 margin-bottom-15">-->
                            <!--<div class="col-md-6">-->
                                <!--<div class="experience-box">-->
                                    <!--<div class="resume" v-if="lastMonthExperience >= 0">-->
                                        <!--<b>+{{ lastMonthExperience.format() }}</b>-->
                                        <!--<span>Last 30 days experience</span>-->
                                    <!--</div>-->

                                    <!--<div class="resume" v-else>-->
                                        <!--<b class="loose">{{ lastMonthExperience.format() }}</b>-->
                                        <!--<span>Last 30 days experience</span>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->

                            <!--<div class="col-md-6">-->
                                <!--<div class="experience-box">-->
                                    <!--<div class="resume" v-if="lastWeekExperience >= 0">-->
                                        <!--<b>+{{ lastWeekExperience.format() }}</b>-->
                                        <!--<span>Last 7 days experience</span>-->
                                    <!--</div>-->

                                    <!--<div class="resume" v-else>-->
                                        <!--<b class="loose">{{ lastWeekExperience.format() }}</b>-->
                                        <!--<span>Last 7 days experience</span>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</el-tab-pane>-->

                    <!--&lt;!&ndash; Total Experience &ndash;&gt;-->
                    <!--<el-tab-pane label="Total Experience" name="total">-->
                        <!--<highcharts class="margin-top-50" id="totalExp" :options="totalExpChart"/>-->
                    <!--</el-tab-pane>-->
                <!--</el-tabs>-->
            <!--</page-load>-->
        <!--</panel>-->

        <experience-table :player="player" />
    </div>
</template>

<script>
    import ExperienceTable from './ExperienceTable'

    export default {
        props: ['experience', 'player', 'loading'],

        components: { ExperienceTable },

        data () {
            return {
                chartTab: 'daily'
            }
        },

        computed: {
            dailyExpChart () {
                return {
                    chart: { type: 'area', height: 233 },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.experience.map(exp => exp.updated_at),
                        crosshair: true,
                        labels: { enabled: false },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: []
                    },
                    yAxis: { min: 0, title: { text: '' } },
                    series: [
                        {
                            name: 'Experience',
                            data: this.experience.map(exp => exp.advance),
                            fillOpacity: '0.3',
                            color: '#3498db',
                        },
                    ],
                    credits: { enabled: false },
                    tooltip: { shared: true },
                    legend: { enabled: false }
                }
            },

            totalExpChart () {
                return {
                    chart: { type: 'area', height: 233 },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.experience.map(exp => exp.updated_at),
                        crosshair: true,
                        labels: { enabled: false },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: []
                    },
                    yAxis: { min: null, title: { text: '' }, startOnTick: false, },
                    series: [
                        {
                            name: 'Experience',
                            data: this.experience.map(exp => exp.experience),
                            fillOpacity: '0.3',
                            color: '#3498db',
                        },
                    ],
                    credits: { enabled: false },
                    tooltip: { shared: true },
                    legend: { enabled: false }
                }
            },

            lastMonthExperience () {
                return this.experience
                    ? this.experience.slice(0, 30).reduce((carry, exp) => carry + exp.advance, 0)
                    : 0
            },

            lastWeekExperience () {
                return this.experience
                    ? this.experience.slice(0, 7).reduce((carry, exp) => carry + exp.advance, 0)
                    : 0
            },
        },

        filters: {
            date (date) {
                return moment(date).format('DD MMM YYYY')
            },

            dateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'America/New_York').fromNow()
            }
        },
    }
</script>