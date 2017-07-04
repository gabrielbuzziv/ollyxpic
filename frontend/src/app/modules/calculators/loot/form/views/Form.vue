<template>
    <page-load>
        <page-title>
            Loot Count
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <panel>
                    <el-select
                            v-model="currentItem"
                            filterable
                            remote
                            default-first-option
                            placeholder="Search the item"
                            popper-class="item-options"
                            class="item-select"
                            loading-text="Loading ..."
                            no-data-text="No items found."
                            no-match-text="Item not found or already selected."
                            :remote-method="searchItem"
                            :loading="loadingItem"
                            @change="addItemToLoot">
                        <el-option
                                v-for="item in items"
                                :key="item.id"
                                :label="item.title"
                                :value="{ id: item.id, name: item.title, value: item.vendor_value, amount: 1 }">
                            <span class="item-option">
                                <img :src="image_path('item', item.id)">
                                {{ item.title }}
                            </span>
                        </el-option>
                    </el-select>
                </panel>

                <panel>
                    <table class="table table-loot">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th class="text-center" width="150">Amount</th>
                                <th class="text-center" width="150">Price</th>
                                <th class="text-center">Total</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody v-if="loots.length">
                            <tr v-for="loot, index in loots">
                                <td width="80">
                                    <img :src="image_path('item', loot.id)">
                                </td>
                                <td>{{ loot.name }}</td>
                                <td class="text-center" width="150">
                                    <div class="input-group">
                                        <form-input :data="loot.amount" v-model="loot.amount" class="text-right"/>
                                        <div class="input-group-addon">x</div>
                                    </div>
                                </td>
                                <td class="text-center" width="150">
                                    <div class="input-group">
                                        <form-input :data="loot.value" v-model="loot.value" class="text-right"/>
                                        <div class="input-group-addon">gp</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ (loot.amount * loot.value).formatMoney(0, '.','.') }} gp
                                </td>
                                <td width="100" class="text-right">
                                    <button class="btn" @click="removeItem(index)">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-info">
                                        Add items to calculate the total amount of loot.
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot v-if="loots.length">
                            <tr class="total">
                                <td colspan="4"></td>
                                <td class="text-center">
                                    {{ lootTotal }} gp
                                    <!--{{ lootTotal.formatMoney(0, '.','.') }} gp-->
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
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
            }
        },

        methods: {
            addItemToLoot (item) {
                if (! isEmpty(item)) {
                    this.loots.push(item)
                    this.currentItem = ''
                    this.items       = []
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
            }, 300),

            removeItem (index) {
                this.loots.splice(index, 1)
            }
        },

        mounted () {

        }
    }
</script>