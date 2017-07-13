<template>
    <page-load id="imbuements">
        <page-title>
            <img :src="item_path('slayer-of-mayhem-overcharged')" alt="">
            Imbuements
            <span>
                Waste/Time
            </span>
        </page-title>

        <div class="col-md-12">
            <panel>
                <table class="table margin-bottom-0">
                    <thead>
                        <tr>
                            <th>Total</th>
                            <th class="text-center">Total/Hour</th>
                            <th class="text-center">Total/Minute</th>
                            <th class="text-center" width="110">
                                Time Used

                                <el-tooltip placement="top">
                                    <p class="margin-bottom-0" slot="content">
                                        Time you used the imbuiment in hunt, time in hours.<br> e.g. 02:00 (2 hours)
                                    </p>
                                    <i class="mdi mdi-information"></i>
                                </el-tooltip>
                            </th>
                            <th class="text-right">Value</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                {{ total.formatMoney(0, '.', '.') }} gp
                            </td>
                            <td class="text-center">
                                {{ (totalPerMinute * 60).formatMoney(0, '.', '.') }} gp / hour
                            </td>
                            <td class="text-center">
                                {{ totalPerMinute.formatMoney(0, '.', '.') }} gp / minute
                            </td>
                            <td class="text-center" width="100">
                                <form-input :data="used" v-model="used" v-mask="'##:##'" class="normal"/>
                            </td>
                            <td class="text-right">
                                {{ totalPerUsedTime.formatMoney(0, '.', '.') }} gp
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>

            <panel>
                <table class="table margin-bottom-0">
                    <thead>
                        <tr>
                            <th>Imbuiment</th>
                            <th class="text-center">
                                Amount
                                <el-tooltip content="Amount of items you have this imbuement." class="margin-left-5"
                                            placement="top">
                                    <i class="mdi mdi-information"></i>
                                </el-tooltip>
                            </th>
                            <th class="text-center">
                                Basic
                                <el-tooltip content="Unit price of basic imbuement item." class="margin-left-5"
                                            placement="top">
                                    <i class="mdi mdi-information"></i>
                                </el-tooltip>
                            </th>
                            <th class="text-center">
                                Intricate
                                <el-tooltip content="Unit price of intricate imbuement item." class="margin-left-5"
                                            placement="top">
                                    <i class="mdi mdi-information"></i>
                                </el-tooltip>
                            </th>
                            <th class="text-center">
                                Powerful
                                <el-tooltip content="Unit price of powerful imbuement item." class="margin-left-5"
                                            placement="top">
                                    <i class="mdi mdi-information"></i>
                                </el-tooltip>
                            </th>
                            <th class="text-center">
                                Charm
                                <el-tooltip content="Protection charm (100% chance)" class="margin-left-5"
                                            placement="top">
                                    <i class="mdi mdi-information"></i>
                                </el-tooltip>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="imbuement in imbuements">
                            <td>
                                <p>{{ imbuement.title }} ({{ imbuement.name }})</p>
                                <small>{{ imbuement.description }}</small>
                            </td>
                            <td class="text-center" width="110">
                                <select v-model="imbuement.amount" class="form-control" @change="update(imbuement)">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </td>
                            <td class="text-center" width="150">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <el-tooltip placement="top">
                                            <template slot="content">
                                                {{getItem(imbuement.items, 1).amount}} {{ getItem(imbuement.items, 1).item.title }}
                                            </template>

                                            <img :src="image_path('item', getItem(imbuement.items, 1).item_id)" alt="">
                                        </el-tooltip>
                                    </div>
                                    <form-input :data="imbuement.basic" v-model="imbuement.basic" placeholder="Basic"
                                                class="text-right" @input="update(imbuement)"/>
                                </div>
                            </td>
                            <td class="text-center" width="150">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <el-tooltip placement="top">
                                            <template slot="content">
                                                {{getItem(imbuement.items, 2).amount}} {{ getItem(imbuement.items, 2).item.title }}
                                            </template>

                                            <img :src="image_path('item', getItem(imbuement.items, 2).item_id)" alt="">
                                        </el-tooltip>
                                    </div>
                                    <form-input :data="imbuement.intricate" v-model="imbuement.intricate"
                                                placeholder="Basic" class="text-right" @input="update(imbuement)"/>
                                </div>
                            </td>
                            <td class="text-center" width="150">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <el-tooltip placement="top">
                                            <template slot="content">
                                                {{getItem(imbuement.items, 3).amount}} {{ getItem(imbuement.items, 3).item.title }}
                                            </template>

                                            <img :src="image_path('item', getItem(imbuement.items, 3).item_id)" alt="">
                                        </el-tooltip>
                                    </div>
                                    <form-input :data="imbuement.powerful" v-model="imbuement.powerful"
                                                placeholder="Basic" class="text-right" @input="update(imbuement)"/>
                                </div>
                            </td>
                            <td class="text-center" width="90">
                                <el-checkbox v-model="imbuement.charm" @change="update(imbuement)"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                imbuements: [],
                imbuement: null,
                used: '00:00'
            }
        },

        computed: {
            total () {
                return this.imbuements.reduce((carry, imbuement) => {
                    const charm = imbuement.charm ? this.getTax(imbuement).charm : 0
                    const value = this.getTax(imbuement).value
                    const total = charm + value + imbuement.items.reduce((carry, item) => {
                                switch (item.tier) {
                                    case 1:
                                        return carry + (item.amount * imbuement.basic)
                                    case 2:
                                        return carry + (item.amount * imbuement.intricate)
                                    case 3:
                                        return carry + (item.amount * imbuement.powerful)
                                }
                            }, 0)

                    return carry + (total * imbuement.amount)
                }, 0)
            },

            totalPerMinute () {
                return this.total / 1200
            },

            totalPerUsedTime () {
                const minutes = window.moment.duration(this.used).asMinutes()
                return this.totalPerMinute * minutes
            },
        },

        methods: {
            getItem (items, tier) {
                if (items.length && items.filter(item => item.tier == tier).length) {
                    return items.filter(item => item.tier == tier)[0]
                }

                return null
            },

            getTax (imbuement) {
                if (imbuement.powerful > 0) {
                    return { value: 100000, charm: 50000 }
                } else if (imbuement.intricate > 0) {
                    return { value: 25000, charm: 30000 }
                } else if (imbuement.basic > 0) {
                    return { value: 5000, charm: 10000 }
                } else {
                    return { value: 0, charm: 0 }
                }
            },

            update (imbuement) {
                localStorage.setItem(`imbuement.${imbuement.id}.amount`, imbuement.amount)
                localStorage.setItem(`imbuement.${imbuement.id}.basic`, imbuement.basic)
                localStorage.setItem(`imbuement.${imbuement.id}.intricate`, imbuement.intricate)
                localStorage.setItem(`imbuement.${imbuement.id}.powerful`, imbuement.powerful)
                localStorage.setItem(`imbuement.${imbuement.id}.charm`, imbuement.charm)

                this.calculate()
            },

            loadImbuiments () {
                services.fecthImbuements()
                        .then(response => {
                            this.imbuements = response.data.map(imbuement => {
                                const amount    = localStorage.getItem(`imbuement.${imbuement.id}.amount`) || 0
                                const basic     = localStorage.getItem(`imbuement.${imbuement.id}.basic`) || 0
                                const intricate = localStorage.getItem(`imbuement.${imbuement.id}.intricate`) || 0
                                const powerful  = localStorage.getItem(`imbuement.${imbuement.id}.powerful`) || 0
                                const charm     = localStorage.getItem(`imbuement.${imbuement.id}.charm`) || false

                                return {
                                    id: imbuement.id,
                                    title: imbuement.title,
                                    name: imbuement.name,
                                    description: imbuement.description,
                                    amount: amount,
                                    basic: basic,
                                    intricate: intricate,
                                    powerful: powerful,
                                    charm: charm,
                                    items: imbuement.items
                                }
                            })
                        })
            }
        },

        mounted () {
            this.loadImbuiments()
        }
    }
</script>