<template>
    <page-load id="currencies">
        <page-title>
            <img :src="image_path_by_name('item', 'tibia coins')" class="margin-right-5">
            Tibia Currency
            <span class="margin-left-45">Stock Exchange</span>
        </page-title>


        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>World</th>
                        <th>World Type</th>
                        <th>Buy Currency</th>
                        <th>Sell Currency</th>
                        <th>Last Update</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="world in worlds">
                        <td>{{ world.name }}</td>
                        <td>{{ world.type }}</td>
                        <td>
                            <span v-if="world.currencies.length">
                                {{ getLastCurrency(world).buy }} gp

                                <span v-if="world.currencies.length > 1">
                                    <span class="currency-increase" v-if="compareLastTwoCurrencies(world, 'buy') > 0">
                                        <i class="mdi mdi-menu-up"></i>
                                        {{ compareLastTwoCurrencies(world, 'buy').toFixed(2) }} %
                                    </span>

                                    <span class="currency-decrease" v-else>
                                        <i class="mdi mdi-menu-down"></i>
                                        {{ compareLastTwoCurrencies(world, 'buy').toFixed(2) }} %
                                    </span>
                                </span>
                            </span>
                        </td>
                        <td>
                            <span v-if="world.currencies.length">
                                {{ getLastCurrency(world).sell }} gp

                                <span v-if="world.currencies.length > 1">
                                    <span class="currency-increase" v-if="compareLastTwoCurrencies(world, 'sell') > 0">
                                        <i class="mdi mdi-menu-up"></i>
                                        {{ compareLastTwoCurrencies(world, 'sell').toFixed(2) }} %
                                    </span>

                                    <span class="currency-decrease" v-else>
                                        <i class="mdi mdi-menu-down"></i>
                                        {{ compareLastTwoCurrencies(world, 'sell').toFixed(2) }} %
                                    </span>
                                </span>
                            </span>
                        </td>
                        <td>
                            <span v-if="world.currencies.length">
                                {{ getDateForHuman(getLastCurrency(world).created_at) }}
                            </span>
                        </td>
                        <td>
                            <highcharts :id="`currency-${world.id}`" :options="getChartOptions(world)" ref="chart" v-if="world.currencies.length"/>
                        </td>
                        <td class="text-right">
                            <router-link :to="{ name: 'calculators.currencies.world', params: { id: world.id } }"
                                         class="btn btn-xs"
                                         title="All currencies"
                                         v-if="world.currencies.length">
                                <i class="mdi mdi-chart-areaspline"></i>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                worlds: []
            }
        },

        methods: {
            getLastCurrency (world) {
                return world.currencies[0]
            },

            compareLastTwoCurrencies (world, currency) {
                if (world.currencies.length > 1) {
                    const last = world.currencies[0]
                    const penult = world.currencies[1]

                    return (100 - ((penult[currency] * 100) / last[currency]))
                }
            },

            getDateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'America/New_York').fromNow()
            },

            getChartOptions (world) {
                return {
                    chart: {
                        backgroundColor: null,
                        borderWidth: 0,
                        type: 'area',
                        margin: [2, 0, 2, 0],
                        width: 120,
                        height: 20,
                        style: {
                            overflow: 'visible'
                        },

                        // small optimalization, saves 1-2 ms each sparkline
                        skipClone: true
                    },
                    title: {
                        text: ''
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        labels: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: [],
                        categories: world.currencies && world.currencies.length ? world.currencies.map(currency => currency.created_at).reverse() : [],
                    },
                    yAxis: {
                        endOnTick: false,
                        startOnTick: false,
                        labels: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        tickPositions: [0]
                    },
                    legend: { enabled: false },
                    tooltip: {
                        shared: true,
                        useHTML: true,
                        hideDelay: 0,
                        positioner: function (w, h, point) {
                            return { x: point.plotX - w / 2, y: point.plotY - h };
                        }
                    },
                    series: [
                        {
                            name: 'Buy',
                            data: world.currencies && world.currencies.length ? world.currencies.map(currency => currency.buy).reverse() : [],
                            fillOpacity: '0.3',
                            color: '#3498db',
                        },
                        {
                            name: 'Sell',
                            data: world.currencies && world.currencies.length ? world.currencies.map(currency => currency.sell).reverse() : [],
                            fillOpacity: '0.5',
                            color: '#27ae60',
                        }
                    ],
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
                                radius: 1,
                                states: {
                                    hover: {
                                        radius: 2
                                    }
                                }
                            },
                            fillOpacity: 0.25
                        },
                        column: {
                            negativeColor: '#910000',
                            borderColor: 'silver'
                        }
                    }
                }
            }
        },

        mounted () {
            services.fetchWorlds()
                .then(response => {
                    this.worlds = response.data
                })
        }
    }
</script>