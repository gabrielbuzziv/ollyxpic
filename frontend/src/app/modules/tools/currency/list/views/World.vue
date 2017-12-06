<template>
    <page-load id="currencies">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.currencies' }" class="btn" exact>
                    <i class="mdi mdi-chevron-left margin-right-5"></i>
                    Back to List
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'tibia coins')" class="margin-right-5">
            <div class="title">
                <h2>Tibia Currency of {{ world.name }}</h2>
                <span>Stock Exchange</span>
            </div>
        </page-title>

        <panel>
            <highcharts id="currency" :options="chartOptions" ref="chart"/>
        </panel>

        <span class="last-update">{{ getDateForHuman(lastCurrency.created_at) }}</span>

        <panel class="currency-now">
            <span class="buy">
                <b>Buy</b>
                <span class="value">{{ lastCurrency.buy }} gp</span>

                <span class="compare" v-if="world.currencies && world.currencies.length > 1">
                    <span class="increase" v-if="compareLastTwoCurrencies('buy') > 0">
                        <i class="mdi mdi-menu-up"></i>
                        {{ compareLastTwoCurrencies('buy').toFixed(2) }} %
                    </span>

                    <span class="decrease" v-else>
                        <i class="mdi mdi-menu-up"></i>
                        {{ compareLastTwoCurrencies('buy').toFixed(2) }} %
                    </span>
                </span>
            </span>

            <span class="sell">
                <b>Sell</b>
                <span class="value">{{ lastCurrency.sell }} gp</span>

                <span class="compare" v-if="world.currencies && world.currencies.length > 1">
                    <span class="increase" v-if="compareLastTwoCurrencies('sell') > 0">
                        <i class="mdi mdi-menu-up"></i>
                        {{ compareLastTwoCurrencies('sell').toFixed(2) }} %
                    </span>

                    <span class="decrease" v-else>
                        <i class="mdi mdi-menu-up"></i>
                        {{ compareLastTwoCurrencies('sell').toFixed(2) }} %
                    </span>
                </span>
            </span>
        </panel>

        <panel v-if="world.currencies && world.currencies.length > 1">
            <table class="table margin-bottom-0">
                <thead>
                    <tr>
                        <th class="text-center">Buy</th>
                        <th class="text-center">Sell</th>
                        <th class="text-center">Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="currency in latestCurrencies">
                        <td class="text-center">{{ currency.buy }} gp</td>
                        <td class="text-center">{{ currency.sell }} gp</td>
                        <td class="text-center">{{ getDateForHuman(currency.created_at) }}</td>
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
                world: {},
            }
        },

        computed: {
            lastCurrency () {
                return this.world.currencies && this.world.currencies.length ? this.world.currencies[0] : {}
            },

            latestCurrencies () {
                return this.world.currencies && this.world.currencies.length ? this.world.currencies.slice(1) : []
            },

            chartOptions () {
                return {
                    chart: { type: 'area' },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.world.currencies && this.world.currencies.length ? this.world.currencies.map(currency => currency.created_at).reverse() : [],
                        crosshair: true,
                        labels: { enabled: false }
                    },
                    yAxis: { min: 0, title: { text: '' } },
                    series: [
                        {
                            name: 'Buy',
                            data: this.world.currencies && this.world.currencies.length ? this.world.currencies.map(currency => currency.buy).reverse() : [],
                            fillOpacity: '0.3',
                            color: '#3498db',
                        },
                        {
                            name: 'Sell',
                            data: this.world.currencies && this.world.currencies.length ? this.world.currencies.map(currency => currency.sell).reverse() : [],
                            fillOpacity: '0.5',
                            color: '#27ae60',
                        }
                    ],
                    credits: { enabled: false },
                    tooltip: { shared: true },
                }
            }
        },

        methods: {
            getDateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'America/New_York').fromNow()
            },

            compareLastTwoCurrencies (currency) {
                if (this.world.currencies && this.world.currencies.length > 1) {
                    const last = this.world.currencies[0]
                    const penult = this.world.currencies[1]

                    return (100 - ((penult[currency] * 100) / last[currency]))
                }
            }
        },

        mounted () {
            services.find(this.$route.params.id)
                .then(response => {
                    this.world = response.data
                })
        }
    }
</script>