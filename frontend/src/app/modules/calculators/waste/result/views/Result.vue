<template>
    <page-load>
        <page-title>
            <img :src="item_path('ultimate-mana-potion')" class="margin-right-10">
            Supplies Result
            <span>Waste</span>
        </page-title>

        <div class="row">
            <div class="col-md-8">
                <panel>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Unity Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>

                        <tbody v-if="supplies('potions').length">
                            <tr>
                                <th></th>
                                <th colspan="4">Potions</th>
                            </tr>

                            <tr v-for="supply in supplies('potions')">
                                <td><img :src="image_path('item', supply.data.id)"></td>
                                <td>{{ supply.data.title }}</td>
                                <td class="text-center">{{ supply.quantity }}</td>
                                <td class="text-center">{{ supply.price.formatMoney(0, '.', '.') }} gps</td>
                                <td class="text-right">{{ (supply.quantity * supply.price).formatMoney(0, '.', '.') }} gps</td>
                            </tr>
                        </tbody>

                        <tbody v-if="supplies('ammunitions').length">
                            <tr>
                                <th></th>
                                <th colspan="4">Ammunitions</th>
                            </tr>

                            <tr v-for="supply in supplies('ammunitions')">
                                <td><img :src="image_path('item', supply.data.id)"></td>
                                <td>{{ supply.data.title }}</td>
                                <td class="text-center">{{ supply.quantity }}</td>
                                <td class="text-center">{{ supply.price.formatMoney(0, '.', '.') }} gps</td>
                                <td class="text-right">{{ (supply.quantity * supply.price).formatMoney(0, '.', '.') }} gps</td>
                            </tr>
                        </tbody>

                        <tbody v-if="supplies('runes').length">
                            <tr>
                                <th></th>
                                <th colspan="4">Runes</th>
                            </tr>

                            <tr v-for="supply in supplies('runes')">
                                <td><img :src="image_path('item', supply.data.id)"></td>
                                <td>{{ supply.data.title }}</td>
                                <td class="text-center">{{ supply.quantity }}</td>
                                <td class="text-center">{{ supply.price.formatMoney(0, '.', '.') }} gps</td>
                                <td class="text-right">{{ (supply.quantity * supply.price).formatMoney(0, '.', '.') }} gps</td>
                            </tr>
                        </tbody>

                        <tbody v-if="supplies('amulets').length">
                            <tr>
                                <th></th>
                                <th colspan="4">Amulets</th>
                            </tr>

                            <tr v-for="supply in supplies('amulets')">
                                <td><img :src="image_path('item', supply.data.id)"></td>
                                <td>{{ supply.data.title }}</td>
                                <td class="text-center">{{ supply.quantity }}</td>
                                <td class="text-center">{{ supply.price.formatMoney(0, '.', '.') }} gps</td>
                                <td class="text-right">{{ (supply.quantity * supply.price).formatMoney(0, '.', '.') }} gps</td>
                            </tr>
                        </tbody>

                        <tbody v-if="supplies('rings').length">
                            <tr>
                                <th></th>
                                <th colspan="4">Rings</th>
                            </tr>

                            <tr v-for="supply in supplies('rings')">
                                <td><img :src="image_path('item', supply.data.id)"></td>
                                <td>{{ supply.data.title }}</td>
                                <td class="text-center">{{ supply.quantity }}</td>
                                <td class="text-center">{{ supply.price.formatMoney(0, '.', '.') }} gps</td>
                                <td class="text-right">{{ (supply.quantity * supply.price).formatMoney(0, '.', '.') }} gps</td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr class="big">
                                <th colspan="4"></th>
                                <th class="text-right">{{ (result.total).formatMoney(0, '.', '.') }} gps</th>
                            </tr>
                        </tfoot>
                    </table>
                </panel>

                <panel title="Share" icon="share" class="panel-share">
                    <share-input :data="shareUrl" />
                </panel>
            </div>

            <div class="col-md-4">
                <panel title="Waste Proportion" icon="chart-pie">
                    <highcharts id="waste" :options="chart" style="height: 300px;"/>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'

    export default {
        data () {
            return {
                result: {
                    items: [],
                    total: 0
                },
                chart: {}
            }
        },

        computed: {
            shareUrl () {
                return window.location.href
            }
        },

        methods: {
            supplies (type) {
                return this.result.items.filter(item => item.type == type)
            },
        },

        mounted () {
            services.find(this.$route.params.id)
                    .then(response => {
                        const result = response.data

                        this.chart = {
                            title: {
                                text: `Total<br>${result.total.formatMoney(0, '.', '.')} gps`,
                                align: 'center',
                                verticalAlign: 'middle',
                                y: - 5,
                                x: 10
                            },
                            plotOptions: {
                                pie: {
                                    dataLabels: {
                                        distance: - 50,
                                        style: {
                                            fontWeight: 'bold',
                                            color: 'white'
                                        }
                                    },
                                }
                            },
                            series: [{
                                type: 'pie',
                                name: 'Waste',
                                innerSize: '50%',
                                data: [
                                    result.potions ? ['Potions', result.potions] : null,
                                    result.ammunitions ? ['Ammunitions', result.ammunitions] : null,
                                    result.runes ? ['Runes', result.runes] : null,
                                    result.amulets ? ['Amulets', result.amulets] : null,
                                    result.rings ? ['Rings', result.rings] : null,
                                ]
                            }],
                            credits: {
                                enabled: false
                            }
                        }

                        this.result = response.data
                    })
        }
    }
</script>