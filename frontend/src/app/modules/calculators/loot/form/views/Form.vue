<template>
    <page-load>
        <page-title>
            Loot Count
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <panel>
                    <p>Select the items you want to calculate, then edit then in the list below.</p>

                    <el-select
                            v-model="currentItem"
                            filterable
                            remote
                            default-first-option
                            placeholder="Type the name of the item to add"
                            popper-class="item-options"
                            class="item-select"
                            loading-text="Loading ..."
                            no-data-text="No items found."
                            no-match-text="Item not found or already selected."
                            :remote-method="searchItem"
                            :loading="loadingItem"
                            @change="addItemToLoot"
                            ref="search">
                        <el-option
                                v-for="item in items"
                                :key="item.id"
                                :label="item.title"
                                :value="{ id: item.id, name: item.title, value: item.vendor_value, amount: 1, sellTo: item.sell_to }">
                            <span class="item-option">
                                <img :src="image_path('item', item.id)">
                                {{ item.title }}
                            </span>
                        </el-option>
                    </el-select>

                    <small class="helper-block margin-top-5 block" v-if="! loots.length">
                        <em>You need to add at least one item to see statistics.</em>
                    </small>
                </panel>

            </div>

            <div class="col-md-9">
                <panel v-if="loots.length">
                    <table class="table table-loot margin-bottom-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">NPC</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="loot, index in loots">
                                <td width="50">
                                    <img :src="image_path('item', loot.id)">
                                </td>
                                <td>{{ loot.name }}</td>
                                <td class="text-center" width="100">
                                    <div class="input-group">
                                        <form-input :data="loot.amount" v-model="loot.amount" class="text-right"/>
                                        <div class="input-group-addon">x</div>
                                    </div>
                                </td>
                                <td class="text-center" width="130">
                                    <div class="input-group">
                                        <form-input :data="loot.value" v-model="loot.value" class="text-right"/>
                                        <div class="input-group-addon">gp</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ (loot.amount * loot.value).formatMoney(0, '.','.') }} gp
                                </td>
                                <td class="text-center">
                                    <el-tooltip>
                                        <div slot="content">
                                            <img :src="image_path('npc', getBestNpc(loot.sellTo).id)" alt="">
                                        </div>

                                        <div>{{ getBestNpc(loot.sellTo).name }}</div>
                                    </el-tooltip>
                                </td>
                                <td width="50" class="text-right">
                                    <button class="btn" @click="removeItem(index)">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </panel>
            </div>

            <div class="col-md-3" v-if="loots.length">
                <panel class="panel-total">
                    <strong>
                        <img :src="item_path('crystal-coin')" class="margin-right-10" v-if="lootTotal >= 100000">
                        <img :src="item_path('platinum-coin')" class="margin-right-10" v-if="lootTotal < 100000 && lootTotal >= 10000">
                        <img :src="item_path('gold-coin')" class="margin-right-10" v-if="lootTotal < 10000">

                        {{ lootTotal.formatMoney(0, '.', '.') }} gp
                    </strong>
                </panel>

                <panel class="npc-items" title="Gren Djinn" v-if="green.length">
                    <img :src="image_path('item', item.id)" v-for="item in green">
                </panel>

                <panel class="npc-items" title="Blue Djinn" v-if="blue.length">
                    <img :src="image_path('item', item.id)" v-for="item in blue">
                </panel>

                <panel class="npc-items" title="Rashid" v-if="rashid.length">
                    <img :src="image_path('item', item.id)" v-for="item in rashid">
                </panel>

                <panel class="npc-items" title="Yasir" v-if="yasir.length">
                    <img :src="image_path('item', item.id)" v-for="item in yasir">
                </panel>

                <panel class="npc-items" title="Others" v-if="others.length">
                    <img :src="image_path('item', item.id)" v-for="item in others">
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                currentItem: '',
                loots: [],
                items: [],
                loadingItem: false
            }
        },

        computed: {
            lootTotal () {
                return this.loots.reduce((carry, loot) => {
                    return carry + (loot.value * loot.amount)
                }, 0)
            },

            green () {
                return this.loots.filter(loot => {
                    return this.getBestNpc(loot.sellTo).name == "Alesar" || this.getBestNpc(loot.sellTo).name == "Yaman"
                })
            },

            blue () {
                return this.loots.filter(loot => {
                    return this.getBestNpc(loot.sellTo).name == "Nah'Bob" || this.getBestNpc(loot.sellTo).name == "Haroun"
                })
            },

            rashid () {
                return this.loots.filter(loot => {
                    return this.getBestNpc(loot.sellTo).name == "Rashid"
                })
            },

            yasir () {
                return this.loots.filter(loot => {
                    return this.getBestNpc(loot.sellTo).name == "Yasir"
                })
            },

            others () {
                return this.loots.filter(loot => {
                    return this.getBestNpc(loot.sellTo).name != "Nah'Bob"
                            && this.getBestNpc(loot.sellTo).name != "Haroun"
                            && this.getBestNpc(loot.sellTo).name != "Yaman"
                            && this.getBestNpc(loot.sellTo).name != "Alesar"
                            && this.getBestNpc(loot.sellTo).name != "Yasir"
                            && this.getBestNpc(loot.sellTo).name != "Rashid"
                })
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
                } else if (this.isMainNPC(buyers, 'Hauron')) {
                    index = this.isMainNPC(buyers, 'Hauron')
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

            addItemToLoot (item) {
                if (! isEmpty(item)) {
                    this.loots.push(item)
                    this.currentItem = ''
                    this.items       = []
                    setTimeout(() => this.$refs.search.$el.querySelector('.el-input input').focus(), 1)
                }
            },

            searchItem: debounce(function (query) {
                if (query.length > 1) {
                    services.searchItem(query)
                            .then(response => {
                                this.items = response.data.filter(item => {
                                    return this.loots.map(loot => loot.id).indexOf(item.id)
                                })
                            })
                }
            }, 200),

            removeItem (index) {
                this.loots.splice(index, 1)
            }
        },
    }
</script>