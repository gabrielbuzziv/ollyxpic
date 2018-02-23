<template>
    <highcharts class="background-chart" :id="id" :options="options"/>
</template>

<script>
    export default {
        props: ['experience', 'month'],

        computed: {
            id () {
                return `month-level-${this.month.date}`
            },

            options () {
                return {
                    chart: {
                        type: 'area',
                        height: 100,
                        backgroundColor: null,
                        borderWidth: 0,
                        margin: [0, 0, 0, 0]
                    },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        crosshair: true,
                        labels: {  enabled: false },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: []
                    },
                    yAxis: {
                        endOnTick: false,
                        startOnTick: false,
                        min: 0,
                        title: { text: '' },
                        tickPositions: [0]
                    },
                    series: [
                        {
                            name: 'Experience',
                            data: this.experience.map(exp => exp.up),
                            fillOpacity: '0.08',
                            color: '#fff',
                            enableMouseTracking: false
                        },
                    ],
                    credits: { enabled: false },
                    tooltip: { enabled: false },
                    legend: { enabled: false },
                    plotOptions: {
                        series: {
                            lineWidth: 0,
                            shadow: false,
                            animation: false,
                            states: {
                                hover: {
                                    lineWidth: 0
                                }
                            },
                            marker: {
                                radius: 0,
                                states: {
                                    hover: {
                                        radius: 0
                                    }
                                }
                            },
                        }
                    }
                }
            }
        }
    }
</script>