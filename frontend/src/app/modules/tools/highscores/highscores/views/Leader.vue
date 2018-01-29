<template>
    <panel class="leader">
        <header>
            <div class="rank">
                <img :src="image_path_by_name('item', thumb)" alt="">
            </div>

            <div class="name">
                <span>{{ leader.name }}</span>
                <router-link :to="{ name: 'tools.players', params: { name: leader.name } }" title="Go to profile">
                    <i class="mdi mdi-chevron-right"></i>
                </router-link>
            </div>
        </header>

        <div class="details">
            <div class="level">
                <b>{{ leader.level.format() }}</b>
                <span>Level</span>
            </div>

            <div class="experience">
                <b>{{ leader.experience.format() }}</b>
                <span>Experience</span>
            </div>
        </div>

        <div class="info">
            <ul>
                <li>
                    <b>Vocation:</b>
                    <span>{{ leader.vocation }}</span>
                </li>

                <li>
                    <b>World:</b>
                    <span>{{ leader.world.name }}</span>
                </li>

                <li class="advances">
                    <highcharts :id="leader.name" :options="chart"/>
                </li>
            </ul>
        </div>
    </panel>
</template>

<script>
    import Types from './Types'
    import services from '../services'

    export default {
        props: ['leader', 'index'],

        data () {
            return {
                details: false
            }
        },

        computed: {
            thumb () {
                switch (this.index) {
                    case 0:
                        return 'Golden Trophy of Excellence'
                    case 1:
                        return 'Silver Trophy of Excellence'
                    case 2:
                        return 'Bronze Trophy of Excellence'
                }
            },

            advances () {
                return this.leader ? this.leader.week_experience : []
            },

            type () {
                const type = this.$route.name.split('.')[2]

                switch (type) {
                    case 'sword':
                        return Types.sword
                    default:
                        return Types.experience
                }
            },

            chart () {
                return {
                    chart: {
                        type: 'area',
                        backgroundColor: null,
                        height: 50,
                        borderWidth: 0,
                        margin: [20, 0, 2, 0],
                        style: { overflow: 'visible' },
                        skipClone: true
                    },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.advances.map(advance => advance.updated_at),
                        labels: { enabled: false },
                        title: { text: null },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: [],
                        grindLineWidth: 0,
                        lineWidth: 0,
                        tickWidth: 0
                    },
                    yAxis: {
                        endOnTick: false,
                        startOnTick: false,
                        labels: { enabled: false },
                        title: { text: null },
                        tickPositions: [0]
                    },
                    series: [
                        {
                            name: this.type.label,
                            data: this.advances.map(advance => advance[this.type.value]),
                            fillOpacity: '0.3',
                            color: '#27ae60',
                        },
                    ],
                    credits: { enabled: false },
                    tooltip: {
                        hideDelay: 0,
                        shared: true,
                        positioner: function (w, h, point) {
                            return { x: point.plotX - w / 2, y: point.plotY - h };
                        },
                        pointFormat: '{series.name}: <b> + {point.y}</b>'
                    },
                    legend: { enabled: false },
                    plotOptions: {
                        series: {
                            animation: false,
                            lineWidth: 1,
                            shadow: false,
                            states: {
                                hover: {
                                    lineWidth: 1
                                }
                            },
                            marker: {
                                radius: 2,
                                states: {
                                    hover: {
                                        radius: 3
                                    }
                                }
                            },
                            fillOpacity: 0.25
                        },
                    }
                }
            }
        },
    }
</script>