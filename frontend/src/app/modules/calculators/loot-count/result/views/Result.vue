<template>
    <page-load class="teamhunt-result">
        <page-title>
            <img :src="image_path('item', 93)">
            Loot
            <span>Count</span>
        </page-title>

        <div class="row">
            <div class="col-md-8">

                <!-- GENERAL INFO -->
                <div class="row">
                    <div class="col-md-4">
                        <score-box icon="coin"
                                   name="Loot"
                                   :score="convertToCrystal(result.loot_total, true)">

                            <button class="btn btn-detail btn-info btn-xs" @click.prevent="lootLog = true" slot="option">
                                <i class="mdi mdi-file-document-box"></i>
                            </button>
                        </score-box>
                    </div>

                    <div class="col-md-4">
                        <score-box icon="arrow-down-bold"
                                   icon-color="#c0392b"
                                   name="Waste"
                                   :score="convertToCrystal(waste, true)">
                        </score-box>
                    </div>

                    <div class="col-md-4">
                        <score-box icon="arrow-up-bold"
                                   icon-color="#27ae60"
                                   name="Profit"
                                   :score="convertToCrystal(profit, true)">
                        </score-box>
                    </div>
                </div>

                <!-- RESULT PANEL -->
                <panel v-if="teammates">
                    <table class="table margin-bottom-0">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Waste</th>
                                <th class="text-center">Profit</th>
                                <th>
                                    Total

                                    <el-tooltip placement="top">
                                        <p class="margin-bottom-0" slot="content">
                                            Waste + Profit
                                        </p>

                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="teammate in result.teammates">
                                <td>{{ teammate.character }}</td>
                                <td width="200">
                                    <div class="input-group">
                                        <form-input class="text-right" :data="teammate.waste" v-model="teammate.waste"
                                                    @input="updateTeammate(teammate)" :readonly="! granted"/>

                                        <div class="input-group-addon">
                                            gp
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ convertToCrystal(profit / teammates, true) }}
                                    <i class="mdi mdi-arrow-down-bold" v-if="(profit / teammates) <= 0"></i>
                                    <i class="mdi mdi-arrow-up-bold" v-if="(profit / teammates) > 0"></i>
                                </td>
                                <td>
                                    {{ convertToCrystal((profit / teammates) + teammate.waste, true) }}

                                    <el-tooltip v-if="convertToCrystal((profit / teammates) + teammate.waste) < 0">
                                        <p class="margin-bottom-0" slot="content">
                                            For waste to be equivalent for all members,
                                            {{ teammate.character }} will have to help with their money.
                                        </p>

                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </panel>

                <!-- LOOT PANEL -->
                <panel>
                    <table class="table table-loot margin-bottom-0">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center" width="90">Amount</th>
                                <th>Item</th>
                                <th class="text-center" width="130">
                                    Unit Price

                                    <el-tooltip placement="top" width="200">
                                        <p class="margin-bottom-0" slot="content">
                                            You can customize the price of each item<br>
                                            in case you sell for players/market.
                                        </p>
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in result.items">
                                <td class="text-center" width="80">
                                    <img :src="image_path('item', item.data.id)">
                                </td>
                                <td>
                                    <form-input class="text-right" :data="item.quantity" v-model="item.quantity"
                                                @input="updateItem(item)" :readonly="! granted"/>
                                </td>
                                <td>
                                    {{ item.data.title }}

                                    <el-popover class="block" placement="right" v-if="item.data.sell_to.length"
                                                trigger="hover">
                                        <div class="npc-popover">
                                            <el-tooltip placement="top" v-for="seller in item.data.sell_to"
                                                        :key="seller.id">
                                                <template slot="content">
                                                    <div class="npc-details">
                                                        <ul>
                                                            <li>Name: {{ seller.name }}</li>
                                                            <li>Live in: {{ seller.city.capitalize() }}</li>
                                                            <li>Job: {{ seller.job }}</li>
                                                        </ul>
                                                    </div>
                                                </template>
                                                <img :src="image_path('npc', seller.id)" class="cursor-inspect">
                                            </el-tooltip>
                                        </div>

                                        <small class="cursor-inspect" slot="reference">
                                            <em>
                                                <i class="mdi mdi-information"></i>
                                                Where can i sell this?
                                            </em>
                                        </small>
                                    </el-popover>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <form-input class="text-right"
                                                    :data="item.unit_price"
                                                    v-model="item.unit_price"
                                                    @input="updateItem(item)"
                                                    :readonly="! granted"/>
                                        <div class="input-group-addon">
                                            gp
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button class="btn" @click="removeItem(item)">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </panel>

                <!-- SHARE PANEL -->
                <panel title="Share" icon="share" class="panel-share" v-if="granted">
                    <share-input :data="shareUrl"/>
                </panel>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-4">
                <panel title="Access Key" icon="key" v-if="granted" toggleable>
                    <form-input type="text"
                                class="text-center"
                                :data="password"
                                v-model="password"
                                readonly
                                ref="password"
                                style="padding: 20px"
                                @click="selectPassword"/>
                </panel>

                <panel title="You're the owner?" icon="key" v-if="! granted">
                    <form action="" @submit.prevent="signPassword">
                        <form-group class="margin-bottom-0">
                            <div class="input-group">
                                <form-input type="password" placeholder="Insert the Acess Key" v-model="password"/>
                                <div class="input-group-btn">
                                    <button class="btn" type="submit">
                                        <i class="mdi mdi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form-group>
                    </form>
                </panel>

                <panel class="npc-items" title="Gren Djinn" v-if="green.length">
                    <img :src="image_path('item', item.data.id)" v-for="item in green">
                </panel>

                <panel class="npc-items" title="Blue Djinn" v-if="blue.length">
                    <img :src="image_path('item', item.data.id)" v-for="item in blue">
                </panel>

                <panel class="npc-items" title="Rashid" v-if="rashid.length">
                    <img :src="image_path('item', item.data.id)" v-for="item in rashid">
                </panel>

                <panel class="npc-items" title="Yasir" v-if="yasir.length">
                    <img :src="image_path('item', item.data.id)" v-for="item in yasir">
                </panel>

                <panel class="npc-items" title="Others" v-if="others.length">
                    <img :src="image_path('item', item.data.id)" v-for="item in others">
                </panel>
            </div>
        </div>

        <el-dialog title="Loot Log" :visible.sync="lootLog" size="large">
            <pre style="overflow: scroll; max-height: 500px;">{{ this.result.loot }}</pre>
        </el-dialog>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        data () {
            return {
                result: {},
                lootLog: false,
                lootBag: '',
                password: '',
                granted: false,
                loading: true
            }
        },

        computed: {
            shareUrl () {
                return window.location.href
            },

            teammates () {
                if (this.result.teammates && this.result.teammates.length) {
                    return this.result.teammates.length
                }

                return 0
            },

            profit () {
                return this.result.loot_total - this.waste
            },

            waste () {
                if (this.teammates) {
                    const waste = this.result.teammates.map(teammate => parseInt(teammate.waste))

                    return waste.reduce((carry, waste) => {
                        return carry + waste;
                    })
                }

                return 0
            },

            npcs () {
                if (this.result && this.result.items) {
                    const flag = []

                    return [].concat.apply([], this.result.items.map(item => {
                        return item.data.sell_to.map(seller => {
                            return {
                                id: seller.id,
                                name: seller.name,
                                city: seller.city
                            }
                        })
                    })).filter(npc => {
                        if (flag[npc.id]) {
                            return false
                        }

                        flag[npc.id] = true
                        return true
                    })
                }
            },

            green () {
                if (this.result && this.result.items) {
                    return this.result.items.filter(item => {
                        if (this.getBestNpc(item.data.sell_to)) {
                            return this.getBestNpc(item.data.sell_to).name == "Alesar" || this.getBestNpc(item.data.sell_to).name == "Yaman"
                        }
                    })
                }

                return 0
            },

            blue () {
                if (this.result && this.result.items) {
                    return this.result.items.filter(item => {
                        if (this.getBestNpc(item.data.sell_to)) {
                            return this.getBestNpc(item.data.sell_to).name == "Nah'Bob" || this.getBestNpc(item.data.sell_to).name == "Haroun"
                        }
                    })
                }

                return 0
            },

            rashid () {
                if (this.result && this.result.items) {
                    return this.result.items.filter(item => {
                        if (this.getBestNpc(item.data.sell_to)) {
                            return this.getBestNpc(item.data.sell_to).name == "Rashid"
                        }
                    })
                }

                return 0
            },

            yasir () {
                if (this.result && this.result.items) {
                    return this.result.items.filter(item => {
                        if (this.getBestNpc(item.data.sell_to)) {
                            return this.getBestNpc(item.data.sell_to).name == "Yasir"
                        }
                    })
                }

                return 0
            },

            others () {
                if (this.result && this.result.items) {
                    return this.result.items.filter(item => {
                        if (item.data.sell_to.length) {
                            if (this.getBestNpc(item.data.sell_to)) {
                                return this.getBestNpc(item.data.sell_to).name != "Nah'Bob"
                                        && this.getBestNpc(item.data.sell_to).name != "Haroun"
                                        && this.getBestNpc(item.data.sell_to).name != "Yaman"
                                        && this.getBestNpc(item.data.sell_to).name != "Alesar"
                                        && this.getBestNpc(item.data.sell_to).name != "Yasir"
                                        && this.getBestNpc(item.data.sell_to).name != "Rashid"
                            }
                        }
                    })
                }

                return 0
            }
        },

        methods: {
            getBestNpc (npcs) {
                let index = 0
                const buyers = npcs.filter(npc => npc.pivot.value == Math.max.apply(Math, npcs.map(npc => npc.pivot.value)))

                if (this.isMainNPC(buyers, 'Yasir')) {
                    index = this.isMainNPC(buyers, 'Yasir')
                } else if (this.isMainNPC(buyers, "Nah'Bob")) {
                    index = this.isMainNPC(buyers, "Nah'Bob")
                } else if (this.isMainNPC(buyers, 'Haroun')) {
                    index = this.isMainNPC(buyers, 'Haroun')
                } else if (this.isMainNPC(buyers, 'Alesar')) {
                    index = this.isMainNPC(buyers, 'Alesar')
                } else if (this.isMainNPC(buyers, 'Yaman')) {
                    index = this.isMainNPC(buyers, 'Yaman')
                } else if (this.isMainNPC(buyers, 'Rashid')) {
                    index = this.isMainNPC(buyers, 'Rashid')
                }

                return buyers[index]
            },

            isMainNPC (npcs, npc) {
                return npcs.map(npc => npc.name).indexOf(npc) > -1 ? npcs.map(npc => npc.name).indexOf(npc) : false
            },

            load (refresh = false) {
                this.loading = true

                services.find(this.$route.params.id, this.password)
                        .then(response => {
                            this.result = response.data

                            if (response.data.password) {
                                this.granted = true
                            }

                            if (refresh) {
                                this.$message({
                                    message: `The teamhunt data was refreshed.`,
                                    type: 'success'
                                })
                            }

                            this.loading = false
                        })
                        .catch(() => {
                            this.loading = false
                        })
            },

            updateItem: debounce(function (item) {
                if (this.granted) {
                    if (item.quantity == 0 || item.quantity == null || item.quantity == '') {
                        this.$confirm('If you change the amount to 0 the item will be deleted from loot and will not be possible to calculate it again.', 'Are you sure?', {
                            type: 'error',
                            cancelButtonText: 'Cancel',
                            confirmButtonText: 'Confirm'
                        }).then(() => {
                            this.updateItemInDatabase(item)
                        }).catch(() => {
                            this.load()
                        })
                    } else {
                        this.updateItemInDatabase(item)
                    }
                }
            }, 300),

            removeItem (item) {
                item.quantity = 0

                this.$confirm('If you remove the item you will not be possible to add it again.', 'Are you sure?', {
                    type: 'error',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm'
                }).then(() => {
                    this.updateItemInDatabase(item)
                }).catch(() => {
                    this.load()
                })
            },

            updateItemInDatabase (item) {
                services.updateItem(this.$route.params.id, item, this.password)
                        .then(response => {
                            this.$message({
                                message: `The Loot list has been updated.`,
                                type: 'success'
                            })
                            this.load()
                        })
            },

            updateTeammate: debounce(function (teammate) {
                if (this.granted) {
                    services.updateTeammate(this.$route.params.id, teammate, this.password)
                            .then(response => {
                                this.$message({
                                    message: `The "${teammate.character}" waste has been updated.`,
                                    type: 'success'
                                })
                                this.load()
                            })
                }
            }, 300),

            signPassword () {
                localStorage.setItem(`hunt_${this.$route.params.id}_password`, this.password)

                this.load()
            },

            selectPassword () {
                this.$refs.password.$el.select()
            }
        },

        mounted () {
            if (this.$route.query.password) {
                localStorage.setItem(`hunt_${this.$route.params.id}_password`, this.$route.query.password)
                this.password = this.$route.query.password
            }

            this.password = localStorage.getItem(`hunt_${this.$route.params.id}_password`)
            this.load()
        }
    }
</script>