<template>
    <page-load id="lootcount" class="teamhunt-result">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.loot.count' }" class="btn btn-success" exact>
                    <i class="mdi mdi-calculator margin-right-5"></i>
                    Calculate another
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'steel boots')">
            <div class="title">
                <h2>Loot</h2>
                <span>Count</span>
            </div>
        </page-title>

        <div class="row headers">
            <div class="col-md-3" :class="{ 'col-md-4': ! teammates.length }">
                <panel class="chart" :class="{ 'with-teammates': teammates.length, 'editing': owner }">
                    <h4>Items per NPC</h4>

                    <section>
                        <highcharts id="npcAmount" :options="amountChartOptions"/>

                        <div class="total">
                            {{ items.length }} items
                        </div>
                    </section>
                </panel>
            </div>

            <div class="col-md-3" :class="{ 'col-md-4': ! teammates.length }">
                <panel class="chart" :class="{ 'with-teammates': teammates.length, 'editing': owner  }">
                    <h4>Loot Value per NPC</h4>

                    <section>
                        <highcharts id="npcValue" :options="valueChartOptions"/>

                        <div class="total">
                            {{ total.format() }} gp
                        </div>
                    </section>
                </panel>
            </div>

            <div :class="{ 'col-md-4': ! teammates.length, 'with-teammates': teammates.length }">
                <div :class="{ 'col-md-2': teammates.length }">
                    <panel class="waste">
                        <h4>Loot</h4>

                        <section>
                            <b>{{ total.format() }} gp</b>
                        </section>
                    </panel>
                </div>

                <div :class="{ 'col-md-2': teammates.length }">
                    <panel class="waste">
                        <h4>Waste</h4>

                        <section>
                            <b>{{ waste.format() }} gp</b>
                        </section>
                    </panel>
                </div>

                <div :class="{ 'col-md-2': teammates.length }">
                    <panel class="profit">
                        <h4>Profit</h4>

                        <section>
                            <b>{{ profit.format() }} gp</b>
                        </section>
                    </panel>
                </div>
            </div>

            <div class="col-md-6" v-if="teammates.length">
                <panel class="teammates">
                    <h4>
                        Teammates

                        <el-tooltip>
                            <template slot="content" placement="bottom-end">
                                <p>
                                    Ollyxpic teammate payment prevent someone waste more then others,<br>
                                    our calcs show the payment for every teammate receive the same waste or profit.
                                </p>
                            </template>

                            <i class="mdi mdi-information"></i>
                        </el-tooltip>
                    </h4>

                    <section>
                        <table class="table">
                            <tr>
                                <th>Character</th>
                                <th>Supplies</th>
                                <th>Profit</th>
                                <th>Payment</th>
                            </tr>

                            <tr v-for="teammate in teammates">
                                <td>{{ teammate.character }}</td>
                                <td width="110">
                                    <input type="text" class="form-control" v-model="teammate.waste" v-if="owner"
                                           @input="updateTeammateWaste(teammate)">
                                    <span v-else>{{ teammate.waste.format() }}</span>
                                </td>
                                <td>{{ getTeammateProfitWaste(teammate).format() }} gp</td>
                                <td>{{ getTeammatePayment(teammate).format() }} gp</td>
                            </tr>
                        </table>
                    </section>
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <panel>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly ref="shareURL" v-model="share">
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="button" @click.prevent="copyShare">Copy URL</button>
                        </div>
                    </div>
                </panel>
            </div>

            <div class="col-md-4">
                <panel>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Password" v-model="password"
                               :readonly="owner">
                        <div class="input-group-btn">
                            <button class="btn btn-primary" @click.prevent="loadPassword" :disabled="owner">
                                Connect
                            </button>
                        </div>
                    </div>
                </panel>
            </div>
        </div>

        <el-tabs>
            <el-tab-pane :label="`All NPC's (${items.length})`">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in items"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Rashid (${rashid.length})`" v-if="rashid.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in rashid"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Blue Djin (${blue.length})`" v-if="blue.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in blue"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Green Djin (${green.length})`" v-if="green.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in green"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Yasir (${yasir.length})`" v-if="yasir.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in yasir"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Telas (${telas.length})`" v-if="telas.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in telas"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

            <el-tab-pane :label="`Others (${others.length})`" v-if="others.length">
                <page-load class="items no-padding row" :loading="loadingItems">
                    <item :info="item" :hunt="result" :password="password" :key="item.id" v-for="item in others"
                          @removed="removeItem"/>
                </page-load>
            </el-tab-pane>

        </el-tabs>
    </page-load>
</template>

<script>
    import Item from './Item'
    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        components: { Item },

        data () {
            return {
                result: {},
                items: [],
                loadingItems: true,
                password: ''
            }
        },

        watch: {
            '$route.params.id' () {
                this.load()
            }
        },

        computed: {
            teammates () {
                return this.result && this.result.teammates ? this.result.teammates : []
            },

            total () {
                return (this.items.reduce((carry, item) => carry + (item.quantity * item.unit_price), 0))
            },

            waste () {
                return this.teammates
                    ? this.teammates.reduce((carry, teammate) => carry + parseInt(teammate.waste), 0)
                    : 0
            },

            profit () {
                return this.total - this.waste
            },

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
                        const filteredNpcs = item.data.sells.filter(sell => sell.value == item.data.vendor_value).map(sell => sell.npc).filter(npc =>
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
                        { name: 'Rashid', y: this.rashid.length, color: '#f39c12' },
                        { name: 'Blue Djin', y: this.blue.length, color: '#2980b9' },
                        { name: 'Green Djin', y: this.green.length, color: '#27ae60' },
                        { name: 'Yasir', y: this.yasir.length, color: '#8e44ad' },
                        { name: 'Telas', y: this.telas.length, color: '#bdc3c7' },
                        { name: 'Others', y: this.others.length, color: '#e74c3c' },
                    ],
                    valuePerNPC: [
                        { name: 'Rashid', y: this.getTotalValue(this.rashid), color: '#f39c12' },
                        { name: 'Blue Djin', y: this.getTotalValue(this.blue), color: '#2980b9' },
                        { name: 'Green Djin', y: this.getTotalValue(this.green), color: '#27ae60' },
                        { name: 'Yasir', y: this.getTotalValue(this.yasir), color: '#8e44ad' },
                        { name: 'Telas', y: this.getTotalValue(this.telas), color: '#bdc3c7' },
                        { name: 'Others', y: this.getTotalValue(this.others), color: '#e74c3c' },
                    ]
                }
            },

            amountChartOptions () {
                return {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: 200,
                        margin: 0
                    },
                    title: {
                        text: '',
                    },
                    tooltip: { pointFormat: '{series.name}: <b>{point.y} items ({point.percentage:.1f}%)</b>' },
                    plotOptions: {
                        pie: {
                            dataLabels: { enabled: false, },
                            allowPointSelect: true,
                            cursor: 'pointer',
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Items per NPC',
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
                        height: 200,
                        margin: 0
                    },
                    title: {
                        text: '',
                    },
                    tooltip: { pointFormat: '{series.name}: <b>{point.y} gp</b>' },
                    plotOptions: {
                        pie: {
                            dataLabels: { enabled: false, },
                            allowPointSelect: true,
                            cursor: 'pointer',
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
            },

            owner () {
                return this.password ? this.password == this.result.password : false
            },

            share () {
                return `http://www.ollyxpic.com/#${this.$route.fullPath}`
            },
        },

        methods: {
            load () {
                services.find(this.$route.params.id, this.password)
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

            getTeammatePayment (teammate) {
                return (this.profit / this.teammates.length) + parseInt(teammate.waste)
            },

            getTeammateProfitWaste (teammate) {
                return this.profit / this.teammates.length
            },

            updateTeammateWaste: debounce(function (teammate) {
                services.updateTeammate(this.result.id, teammate, this.$route.query.password)
                    .then(response => {
                        this.$message.success(`${teammate.character} supplies value updated.`)
                    })
            }, 300),

            removeItem (item) {
                const index = this.items.indexOf(item)
                this.items.splice(index, 1)
            },

            copyShare () {
                const shareURL = this.$refs.shareURL

                shareURL.select()
                document.execCommand('Copy')
            },

            loadPassword () {
                localStorage.setItem(`loot.count.${this.result.id}`, this.password)
                this.load()

                if (this.password == this.result.password) {
                    this.$message.success('Connected')
                }
            }
        },

        mounted () {
            this.password = this.$route.query.password
                ? this.$route.query.password
                : localStorage.getItem(`loot.count.${this.$route.params.id}`)
                    ? localStorage.getItem(`loot.count.${this.$route.params.id}`)
                    : ''

            this.load()
        }
    }
</script>