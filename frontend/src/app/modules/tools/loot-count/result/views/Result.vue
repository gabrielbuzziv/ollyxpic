<template>
    <page-load id="lootcount" class="teamhunt-result">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.loot.count' }" class="btn" exact>
                    <i class="mdi mdi-arrow-left margin-right-5"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'steel boots')">
            <div class="title">
                <h2>Loot</h2>
                <span>Count</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-6">
                <panel>
                    Loot

                    <section class="row">
                        <div class="col-md-6">
                            <highcharts id="npcAmount" :options="amountChartOptions"/>
                        </div>

                        <div class="col-md-6">
                            <highcharts id="npcValue" :options="valueChartOptions"/>
                        </div>
                    </section>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    Waste
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    Profit
                </panel>
            </div>
        </div>

        <el-tabs>
            <el-tab-pane :label="`All NPC's (${items.length})`">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in items"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Rashid (${rashid.length})`" v-if="rashid.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in rashid"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Blue Djin (${blue.length})`" v-if="blue.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in blue"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Green Djin (${green.length})`" v-if="green.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in green"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Yasir (${yasir.length})`" v-if="yasir.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in yasir"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Telas (${telas.length})`" v-if="telas.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in telas"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Others (${others.length})`" v-if="others.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :key="item.id" v-for="item in others"/>
                </page-load>
            </el-tab-pane>

        </el-tabs>
    </page-load>
</template>

<script>
    import Item from './Item'
    import services from '../services'

    export default {
        components: { Item },

        data () {
            return {
                result: {},
                items: [],
                loadingItems: true
            }
        },

        computed: {
            rashid () {
                return this.items && this.items.length
                    ? this.items.filter(item => {
                        return item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc => npc.name == 'Rashid').length
                    })
                    : []
            },

            blue () {
                return this.items && this.items.length
                    ? this.items.filter(item => {
                        return item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc => npc.name == 'Nah\'Bob' || npc.name == 'Haroun').length
                    })
                    : []
            },

            green () {
                return this.items && this.items.length
                    ? this.items.filter(item => {
                        return item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc => npc.name == 'Alesar' || npc.name == 'Yaman').length
                    })
                    : []
            },

            yasir () {
                return this.items && this.items.length
                    ? this.items.filter(item => {
                        return item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc => npc.name == 'Yasir').length
                    })
                    : []
            },

            telas () {
                return this.items && this.items.length
                    ? this.items.filter(item => {
                        return item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc => npc.name == 'Telas').length
                    })
                    : []
            },

            others () {
                return this.items && this.items.length
                    ? this.items.filter(item => {
                        const npcs = item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).length
                        const filteredNpcs =  item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc =>
                            npc.name != 'Rashid' && npc.name != 'Nah\'Bob'
                            && npc.name != 'Haroun' && npc.name != 'Alesar'
                            && npc.name != 'Yaman' && npc.name != 'Yasir'
                            && npc.name != 'Telas'
                        ).length

                        return npcs == filteredNpcs
                    })
                    : []
            },

            statistics () {
                return {
                    itemsPerNPC: [
                        ['Rashid', (this.rashid.length * 100) / this.items.length],
                        ['Blue Djin', (this.blue.length * 100) / this.items.length],
                        ['Green Djin', (this.green.length * 100) / this.items.length],
                        ['Yasir', (this.yasir.length * 100) / this.items.length],
                        ['Telas', (this.telas.length * 100) / this.items.length],
                        ['Others', (this.others.length * 100) / this.items.length],
                    ],
                    valuePerNPC: [
                        ['Rashid', this.getTotalValue(this.rashid)],
                        ['Blue Djin', this.getTotalValue(this.blue)],
                        ['Green Djin', this.getTotalValue(this.green)],
                        ['Yasir', this.getTotalValue(this.yasir)],
                        ['Telas', this.getTotalValue(this.telas)],
                        ['Others', this.getTotalValue(this.others)],
                    ]
                }
            },

            amountChartOptions () {
                return {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: 200
                    },
                    title: {
                        text: 'Items',
                        align: 'center',
                        verticalAlign: 'middle',
                        y: 40
                    },
                    tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>' },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: false,
                            },
                            startAngle: -90,
                            endAngle: 90,
                            center: ['50%', '75%']
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Amount of items',
                        innerSize: '50%',
                        data: this.statistics.itemsPerNPC
                    }],
                    credits: { enabled: false }
                }
            },

            valueChartOptions () {
                return {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: 200
                    },
                    title: {
                        text: 'Money',
                        align: 'center',
                        verticalAlign: 'middle',
                        y: 40
                    },
                    tooltip: { pointFormat: '{series.name}: <b>{point.y} gp</b>' },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: false,
                            },
                            startAngle: -90,
                            endAngle: 90,
                            center: ['50%', '75%']
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Total in GP\'s',
                        innerSize: '50%',
                        data: this.statistics.valuePerNPC
                    }],
                    credits: { enabled: false }
                }
            }
        },

        methods: {
            load () {
                services.find(this.$route.params.id)
                    .then(response => {
                        this.result = response.data

                        services.fetchItems(response.data.id)
                            .then(response => {
                                this.items = response.data
                                this.loadingItems = false
                            })
                            .catch(() => this.loadingItems = false)
                    })
            },

            getTotalValue (items) {
                return items.reduce((carry, item) => {
                    return carry + (item.quantity * item.unit_price)
                }, 0)
            },
        },

        mounted () {
            this.load()
        }
    }
</script>