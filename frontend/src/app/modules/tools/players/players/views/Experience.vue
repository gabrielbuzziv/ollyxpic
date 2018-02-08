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
        <panel>
            <page-load class="no-padding" :loading="loading">
                <el-tabs v-model="chartTab">
                    <el-tab-pane label="Daily Exp" name="daily">
                        <highcharts class="margin-top-50" id="dailyExp" :options="dailyExpChart"/>
                    </el-tab-pane>
                    <el-tab-pane label="Total Experience" name="total">
                        <highcharts class="margin-top-50" id="totalExp" :options="totalExpChart"/>
                    </el-tab-pane>
                </el-tabs>
            </page-load>
        </panel>

        <div class="row">
            <div class="col-md-6">
                <panel class="experience-box">
                    <page-load class="no-padding" :loading="loading">
                        <div class="resume" v-if="lastMonthExperience >= 0">
                            <b>+{{ lastMonthExperience.format() }}</b>
                            <span>Last 30 days experience</span>
                        </div>

                        <div class="resume" v-else>
                            <b class="loose">{{ lastMonthExperience.format() }}</b>
                            <span>Last 30 days experience</span>
                        </div>
                    </page-load>
                </panel>
            </div>

            <div class="col-md-6">
                <panel class="experience-box">
                    <page-load class="no-padding" :loading="loading">
                        <div class="resume" v-if="lastWeekExperience >= 0">
                            <b>+{{ lastWeekExperience.format() }}</b>
                            <span>Last 7 days experience</span>
                        </div>

                        <div class="resume" v-else>
                            <b class="loose">{{ lastWeekExperience.format() }}</b>
                            <span>Last 7 days experience</span>
                        </div>
                    </page-load>
                </panel>
            </div>
        </div>

        <panel class="advances">
            <page-load class="no-padding" :loading="loading">
                <el-table class="advances"
                          :data="experience"
                          :default-sort="{ prop: 'updated_at', order: 'descending' }">
                    <el-table-column prop="updated_at" label="Date" sortable>
                        <template slot-scope="scope">
                            <b>{{ scope.row.updated_at | date }}</b>
                            <span>{{ scope.row.updated_at | dateForHuman }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column prop="level" label="Level" class-name="level" sortable>
                        <template slot-scope="scope">
                            <span>Level</span>
                            <b>{{ scope.row.level }}</b>

                            <el-tooltip placement="right">
                            <span slot="content">
                                <p class="margin-bottom-5">
                                    You have {{ getExperienceBar(scope.row).percentage }}% to go.
                                </p>
                                <p class="margin-bottom-0">
                                    {{ getExperienceBar(scope.row).leftExperience.format() }}
                                    experience points for next level.
                                </p>
                            </span>
                                <el-progress :percentage="getExperienceBar(scope.row).percentage"
                                             :show-text="false"
                                             v-if="experience.length"/>
                            </el-tooltip>
                        </template>
                    </el-table-column>

                    <el-table-column prop="experience" label="Experience" sortable>
                        <template slot-scope="scope">
                            <b>{{ scope.row.experience.format() }}</b>
                            <span>Experience</span>
                        </template>
                    </el-table-column>

                    <el-table-column prop="advance" label="Advances" class-name="advance" sortable>
                        <template slot-scope="scope">
                            <div v-if="scope.row.advance >= 0">
                                <b>+{{ scope.row.advance.format() }}</b>
                                <span>+ Experience</span>
                            </div>

                            <div v-else>
                                <b class="loose">{{ scope.row.advance.format() }}</b>
                                <span>+ Experience</span>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </page-load>
        </panel>
    </div>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    export default {
        props: ['experience', 'loading'],

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
//                        categories: this.experience.map(exp => exp.updated_at),
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

        methods: {
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
        }
    }
</script>