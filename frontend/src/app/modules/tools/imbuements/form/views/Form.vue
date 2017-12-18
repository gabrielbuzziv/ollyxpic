<template>
    <page-load id="imbuements">
        <page-title>
            <img :src="image_path_by_name('item', 'silencer claws')">
            <div class="title">
                <h2>Imbuements</h2>
                <span>Waste/Time</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <panel>
                    <table class="table margin-bottom-0">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th class="text-center">Total/Hour</th>
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
                                    <b>Material:</b> {{ total | money }} gp

                                    <span class="best" v-if="bestChoice == 'material'">
                                        <img :src="image_path_by_name('item', 'Bag of Oriental Spices')" class="margin-left-15">
                                        Best Choice
                                    </span>
                                </td>
                                <td class="text-center">{{ totalPerMinute * 60 | money }} gp / hour</td>
                                <td class="text-center" width="100">
                                    <form-input :data="used" v-model="used" v-mask="'##:##'" class="normal"/>
                                </td>
                                <td class="text-right">{{ totalPerUsedTime | money }} gp</td>
                            </tr>

                            <tr v-if="goldTokenActive">
                                <td>
                                    <b>Gold Token:</b> {{ totalGoldTokenValue | money }} gp

                                    <span class="best" v-if="bestChoice == 'token'">
                                        <img :src="image_path_by_name('item', 'Bag of Oriental Spices')" class="margin-left-15">
                                        Best Choice
                                    </span>
                                </td>
                                <td class="text-center">{{ totalGoldTokenPerMinute * 60 | money }} gp / hour</td>
                                <td class="text-center" width="100">
                                    <form-input :data="used" v-model="used" v-mask="'##:##'" class="normal"/>
                                </td>
                                <td class="text-right">{{ totalGoldTokenPerUsedTime | money }} gp</td>
                            </tr>
                        </tbody>
                    </table>
                </panel>

                <panel class="gold-token">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <img :src="image_path_by_name('item', 'gold token')" alt="">
                                </div>

                                <input type="text" class="form-control" placeholder="Gold Token price in gps."
                                       v-model="goldToken">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <el-input-number v-model="goldTokenAmount" :min="1" :max="10"/>
                        </div>

                        <div class="col-md-4">
                            <el-radio-group v-model="goldTokenType">
                                <el-radio-button label="Basic"></el-radio-button>
                                <el-radio-button label="Intricate"></el-radio-button>
                                <el-radio-button label="Powerful"></el-radio-button>
                            </el-radio-group>
                        </div>

                        <div class="col-md-2" @change="toggleGoldToken">
                            <el-switch v-model="goldTokenActive"/>
                        </div>
                    </div>
                </panel>

                <panel>
                    <table class="table margin-bottom-0">
                        <thead>
                            <tr>
                                <th>Imbuiment</th>
                                <th class="text-center">
                                    Amount
                                    <el-tooltip content="Number of imbuements." class="margin-left-5"
                                                placement="top">
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </th>
                                <th class="text-center">
                                    Basic
                                    <el-tooltip content="Unit price for basic imbuement materials."
                                                class="margin-left-5"
                                                placement="top">
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </th>
                                <th class="text-center">
                                    Intricate
                                    <el-tooltip content="Unit price for intricate imbuement materials."
                                                class="margin-left-5"
                                                placement="top">
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </th>
                                <th class="text-center">
                                    Powerful
                                    <el-tooltip content="Unit price for powerful imbuement materials."
                                                class="margin-left-5"
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
                                    <p class="inline">{{ imbuement.title }} ({{ imbuement.name }})</p>
                                    <el-tooltip :content="imbuement.description">
                                        <i class="mdi mdi-information inline"></i>
                                    </el-tooltip>
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
                                                    {{getItem(imbuement.items, 1).amount}} {{ getItem(imbuement.items, 1).item.title
                                                    }}
                                                </template>

                                                <img :src="image_path('item', getItem(imbuement.items, 1).item_id)"
                                                     alt="">
                                            </el-tooltip>
                                        </div>
                                        <form-input :data="imbuement.basic" v-model="imbuement.basic"
                                                    placeholder="Basic"
                                                    class="text-right" @input="update(imbuement)"/>
                                    </div>
                                </td>
                                <td class="text-center" width="150">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <el-tooltip placement="top">
                                                <template slot="content">
                                                    {{getItem(imbuement.items, 2).amount}} {{ getItem(imbuement.items, 2).item.title
                                                    }}
                                                </template>

                                                <img :src="image_path('item', getItem(imbuement.items, 2).item_id)"
                                                     alt="">
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
                                                    {{getItem(imbuement.items, 3).amount}} {{ getItem(imbuement.items, 3).item.title
                                                    }}
                                                </template>

                                                <img :src="image_path('item', getItem(imbuement.items, 3).item_id)"
                                                     alt="">
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
        </div>
    </page-load>
</template>

<script type="text/babel">
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                imbuements: [],
                imbuement: null,
                used: '00:00',
                goldTokenActive: false,
                goldToken: 0,
                goldTokenAmount: 0,
                goldTokenType: 'Basic'
            }
        },

        computed: {
            bestChoice () {
                return this.total < this.totalGoldTokenValue ? 'material' : 'token'
            },

            goldTokenTypeValue () {
                switch (this.goldTokenType) {
                    case 'Basic':
                        return 2
                    case 'Intricate':
                        return 4
                    case 'Powerful':
                        return 6
                }
            },

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

            totalGoldTokenValue () {
                return this.goldTokenActive
                    ? (this.goldToken * this.goldTokenTypeValue * this.goldTokenAmount) + this.goldTokenTax + this.goldTokenCharm
                    : 0
            },

            totalGoldTokenPerMinute () {
                return this.totalGoldTokenValue / 1200
            },

            totalGoldTokenPerUsedTime () {
                const minutes = window.moment.duration(this.used).asMinutes()
                return this.totalGoldTokenPerMinute * minutes
            },

            goldTokenTax () {
                switch (this.goldTokenType) {
                    case 'Basic':
                        return 5000
                    case 'Intricate':
                        return 25000
                    case 'Powerful':
                        return 100000
                }
            },

            goldTokenCharm () {
                switch (this.goldTokenType) {
                    case 'Basic':
                        return 10000
                    case 'Intricate':
                        return 30000
                    case 'Powerful':
                        return 50000
                }
            }
        },

        watch: {
            goldToken () {
                if (this.goldToken == 0 || this.goldToken == '' || this.goldToken == null) {
                    this.goldTokenActive = false
                }
            }
        },

        filters: {
            money (data) {
                return data.format()
            }
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
            },

            loadImbuiments () {
                services.fecthImbuements()
                    .then(response => {
                        this.imbuements = response.data.map(imbuement => {
                            const amount = localStorage.getItem(`imbuement.${imbuement.id}.amount`) || 0
                            const basic = localStorage.getItem(`imbuement.${imbuement.id}.basic`) || 0
                            const intricate = localStorage.getItem(`imbuement.${imbuement.id}.intricate`) || 0
                            const powerful = localStorage.getItem(`imbuement.${imbuement.id}.powerful`) || 0
                            const charm = localStorage.getItem(`imbuement.${imbuement.id}.charm`) || false

                            return {
                                id: imbuement.id,
                                title: imbuement.title,
                                name: imbuement.name,
                                description: imbuement.description,
                                amount: amount,
                                basic: basic,
                                intricate: intricate,
                                powerful: powerful,
                                charm: charm == 'true' ? true : false,
                                items: imbuement.items
                            }
                        })
                    })
            },

            toggleGoldToken () {
                if (this.goldTokenActive) {
                    if (this.goldToken == 0 || this.goldToken == '' || this.goldToken == null) {
                        this.$message.error('Fill the gold token price.')
                        this.goldTokenActive = false
                    }
                }
            }
        },

        mounted () {
            this.loadImbuiments()
        }
    }
</script>