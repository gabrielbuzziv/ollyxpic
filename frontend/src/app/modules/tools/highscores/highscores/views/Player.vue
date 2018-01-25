<template>
    <tr>
        <td>{{ index + 4 }}</td>
        <td>{{ player.name }}</td>
        <td>{{ player.level.format() }}</td>
        <td>{{ player.experience.format() }}</td>
        <td>{{ player.vocation }}</td>
        <td>{{ player.world.name }}</td>
    </tr>
</template>

<script>
    import Types from './Types'
    import services from '../services'

    export default {
        props: ['player', 'index'],

        data () {
            return {
                advances: []
            }
        },

        computed: {
            type () {
                const type = this.$route.name.split('.')[2]

                switch (type) {
                    case 'sword':
                        return Types.sword
                    default:
                        return Types.experience
                }
            },

//            chart () {
//                return {
//                    chart: {
//                        type: 'area',
//                        backgroundColor: null,
//                        height: 50,
//                        borderWidth: 0,
//                        margin: [20, 0, 2, 0],
//                        style: { overflow: 'visible' },
//                        skipClone: true
//                    },
//                    title: { text: '' },
//                    subtitle: { text: '' },
//                    xAxis: {
//                        categories: this.advances.map(advance => advance.updated_at),
//                        labels: { enabled: false },
//                        title: { text: null },
//                        startOnTick: false,
//                        endOnTick: false,
//                        tickPositions: [],
//                        grindLineWidth: 0,
//                        lineWidth: 0,
//                        tickWidth: 0
//                    },
//                    yAxis: {
//                        endOnTick: false,
//                        startOnTick: false,
//                        labels: { enabled: false },
//                        title: { text: null },
//                        tickPositions: [0]
//                    },
//                    series: [
//                        {
//                            name: this.type.label,
//                            data: this.advances.map(advance => advance[this.type.value]),
//                            fillOpacity: '0.3',
//                            color: '#27ae60',
//                        },
//                    ],
//                    credits: { enabled: false },
//                    tooltip: {
//                        hideDelay: 0,
//                        shared: true,
//                        positioner: function (w, h, point) {
//                            return { x: point.plotX - w / 2, y: point.plotY - h };
//                        },
//                        pointFormat: '{series.name}: <b> + {point.y}</b>'
//                    },
//                    legend: { enabled: false },
//                    plotOptions: {
//                        series: {
//                            animation: false,
//                            lineWidth: 2,
//                            shadow: false,
//                            states: {
//                                hover: {
//                                    lineWidth: 2
//                                }
//                            },
//                            marker: {
//                                radius: 3,
//                                states: {
//                                    hover: {
//                                        radius: 4
//                                    }
//                                }
//                            },
//                            fillOpacity: 0.25
//                        },
//                    }
//                }
//            }
        }
    }
</script>