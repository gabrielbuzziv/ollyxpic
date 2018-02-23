<template>
    <div :id="id" class="highchart"></div>
</template>

<script type="text/babel">
    import Highcharts from 'highcharts'

    export default {
        props: {
            id: {
                type: String,
                required: true
            },

            options: Object
        },

        data () {
            return {
                chart: null
            }
        },

        watch: {
            options () {
                this.init()
            }
        },

        methods: {
            init () {
                this.chart = Highcharts.chart(this.id, this.options)
            }
        },

        mounted () {
            Vue.nextTick(() => {
                this.init()
                this.chart.reflow()
            })
        },
    }
</script>
