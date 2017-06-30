<template>
    <page-load class="teamhunt-result">
        <page-title>
            Team Hunt Calculator Result
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
                <panel title="Teammates" icon="account-multiple" toggleable v-if="teammates">
                    <table class="table">
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
                <panel title="Loot" toggleable>
                    <table class="table table-loot">
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
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in result.items">
                                <td class="text-center" width="80">
                                    <img :src="item.data.image_url">
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
                <panel title="NPC's to Visit" icon="cart" toggleable>
                    <table class="table">
                        <tbody>
                            <tr v-for="npc in npcs">
                                <td>
                                    <img :src="image_path('npc', npc.id)" alt="">
                                </td>
                                <td><b>{{ npc.name }}</b></td>
                                <td>{{ npc.city | capitalize }}</td>
                            </tr>
                        </tbody>
                    </table>
                </panel>

                <panel title="Access Key" icon="key" v-if="granted" toggleable :open="false">
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

                <!-- Filters -->
                <panel title="Filters" icon="filter" class="panel-filters" toggleable :open="false">
                    <ul>
                        <li>
                            <b>Cost Effective:</b>
                            <div class="pull-right">
                                <div class="label label-success" v-if="result.effective">Enabled</div>
                                <div class="label label-danger" v-else>Disabled</div>
                            </div>
                        </li>

                        <li>
                            <b>Creature Products:</b>
                            <div class="pull-right">
                                <div class="label label-success" v-if="result.stackable">Enabled</div>
                                <div class="label label-danger" v-else>Disabled</div>
                            </div>
                        </li>

                        <li>
                            <b>Gold Coins:</b>
                            <div class="pull-right">
                                <div class="label label-success" v-if="result.goldcoins">Enabled</div>
                                <div class="label label-danger" v-else>Disabled</div>
                            </div>
                        </li>

                        <li>
                            <b>Valuables:</b>
                            <div class="pull-right">
                                <div class="label label-success" v-if="result.valuables">
                                    above {{ result.valuables }}
                                </div>
                                <div class="label label-danger" v-else>Disabled</div>
                            </div>
                        </li>
                    </ul>

                    <div class="clearfix"></div>
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
            }
        },

        methods: {
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

            updateItemInDatabase (item) {
                services.updateItem(this.$route.params.id, item, this.password)
                        .then(response => {
                            this.$message({
                                message: `The item "${item.data.title}" has been updated.`,
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
            this.password = localStorage.getItem(`hunt_${this.$route.params.id}_password`)
            this.load()
        }
    }
</script>