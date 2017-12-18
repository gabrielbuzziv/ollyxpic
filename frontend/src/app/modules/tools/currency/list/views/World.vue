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

        <div class="row">
            <div class="col-md-6">
                <panel title="Buy Price">
                    <page-load class="no-padding" :loading="loading">
                        <highcharts id="buyCurrency" :options="buyChartOptions" ref="buy"/>
                    </page-load>
                </panel>
            </div>

            <div class="col-md-6">
                <panel title="Sell Price">
                    <page-load class="no-padding" :loading="loading">
                        <highcharts id="sellCurrency" :options="sellChartOptions" ref="sell"/>
                    </page-load>
                </panel>
            </div>
        </div>

        <panel>
            <page-load class="no-padding" :loading="loading">
                <table class="table margin-bottom-0">
                    <thead>
                        <tr>
                            <th>Buy Currency</th>
                            <th>Sell Currency</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr :class="[index == 0 ? 'current' : '']" v-for="currency, index in world.currencies">
                            <td>{{ currency.buy | currency }}</td>
                            <td>{{ currency.sell | currency }}</td>
                            <td>{{ currency.created_at | diff_humans }}</td>
                        </tr>
                    </tbody>
                </table>
            </page-load>
        </panel>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../services'

    export default {
        data () {
            return {
                world: {},
                loading: true,
            }
        },

        computed: {
            lastCurrency () {
                return this.world.currencies && this.world.currencies.length ? this.world.currencies[0] : {}
            },

            latestCurrencies () {
                return this.world.currencies && this.world.currencies.length ? this.world.currencies.slice(1) : []
            },

            buyChartOptions () {
                return {
                    chart: { type: 'area', height: 200 },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.world.currencies && this.world.currencies.length ? this.world.currencies.map(currency => currency.created_at).reverse() : [],
                        crosshair: true,
                        labels: { enabled: false },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: []
                    },
                    yAxis: { min: 0, title: { text: '' } },
                    series: [
                        {
                            name: 'Price',
                            data: this.world.currencies && this.world.currencies.length
                                ? this.world.currencies.map(currency => currency.buy).reverse()
                                : [],
                            fillOpacity: '0.3',
                            color: '#3498db',
                        },
                    ],
                    credits: { enabled: false },
                    tooltip: { shared: true },
                    legend: { enabled: false }
                }
            },

            sellChartOptions () {
                return {
                    chart: { type: 'area', height: 200 },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: {
                        categories: this.world.currencies && this.world.currencies.length
                            ? this.world.currencies.map(currency => currency.created_at).reverse()
                            : [],
                        crosshair: true,
                        labels: { enabled: false },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: []
                    },
                    yAxis: {
                        min: 0,
                        title: { text: '' },
                    },
                    series: [
                        {
                            name: 'Price',
                            data: this.world.currencies && this.world.currencies.length ? this.world.currencies.map(currency => currency.sell).reverse() : [],
                            fillOpacity: '0.3',
                            color: '#3498db',
                        }
                    ],
                    credits: { enabled: false },
                    tooltip: { shared: true },
                    legend: { enabled: false }
                }
            }
        },

        filters: {
            currency (data) {
                return data ? `${data.format()} gp` : '-'
            },

            diff_humans (data) {
                return moment(data).fromNow()
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
                    this.loading = false
                })
                .catch(() => this.loading = false)
        }
    }
</script>