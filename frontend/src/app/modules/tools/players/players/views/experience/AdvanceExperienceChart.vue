<template>
    <highcharts class="margin-top-50" id="advanced-experience-chart" :options="options"/>
</template>

<script>
    export default {
        props: ['experience'],

        computed: {
            advance () {
                return this.experience.slice().sort((a, b) => a.id - b.id)
            },

            options () {
                return {
                    chart: { type: 'area', height: 233 },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.advance.map(exp => exp.updated_at),
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
                            data: this.advance.map(exp => exp.experience),
                            fillOpacity: '0.3',
                            color: '#3498db',
                        },
                    ],
                    credits: { enabled: false },
                    tooltip: { shared: true },
                    legend: { enabled: false }
                }
            }
        }
    }
</script>