<template>
    <page-load id="blessings">
        <page-title>
            <img :src="item_path('spiritual-charm')" alt="">
            Blessing
            <span>Penalty</span>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <panel>
                    <div class="row">
                        <div class="col-md-6">
                            <form-input class="level" v-model="settings.level" placeholder="Level"/>
                        </div>

                        <div class="col-md-6">
                            <el-checkbox v-model="settings.promoted">Promoted?</el-checkbox>
                        </div>
                    </div>
                </panel>
            </div>

            <div class="col-md-6">
                <panel>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Amount of Blessings</th>
                                <th class="text-center">Death Penalty</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="bless in blesses">
                                <td class="text-center">
                                    <div v-if="bless == 0">No blessing</div>
                                    <div v-if="bless == 1">{{ bless }} blessing</div>
                                    <div v-if="bless > 1">{{ bless }} blessings</div>
                                </td>
                                <td class="text-center">{{ getPenalty(bless) }} exp</td>
                            </tr>
                        </tbody>
                    </table>
                </panel>
            </div>

            <div class="col-md-6">
                <panel>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Bless</th>
                                <th class="text-center">Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="bless in prices">
                                <td class="text-center">{{ bless.name }}</td>
                                <td class="text-center">{{ getPrice(bless.name).formatMoney(0, '.', '.') }} gp</td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td class="text-center">Total</td>
                                <td class="text-center">{{ totalBless.formatMoney(0, '.', '.') }} gp</td>
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
                settings: {
                    level: 0,
                    promoted: false
                },

                blesses: [0, 1, 2, 3, 4, 5, 6, 7],
                prices: [
                    { name: 'Spiritual Shielding' },
                    { name: 'Embrace of TIbia' },
                    { name: 'Fire of the Suns' },
                    { name: 'Wisdom of Solitude' },
                    { name: 'Spark of the Phoenix' },
                    { name: 'Blood of the Mountain' },
                    { name: 'Heart of the Mountain' },
                ]
            }
        },

        computed: {
            experience () {
                const level = this.settings.level

                return (50 * Math.pow(level, 3) / 3) - (100 * Math.pow(level, 2)) + (850 * level / 3) - 200
            },

            totalBless () {
                return this.prices.reduce((carry, bless) => {
                    return carry + parseInt(this.getPrice(bless.name))
                }, 0)
            }
        },

        methods: {
            getPenalty (bless) {
                const level      = parseInt(this.settings.level)
                const promoted   = this.settings.promoted
                const experience = this.experience

                if (level > 0) {
                    let penalty = 0

                    if (level < 24) {
                        penalty = experience * 0.10
                    } else {
                        penalty = ((level + 50) / 100) * (50 * (Math.pow(level, 2) - (5 * level) + 8))
                    }

                    const promotedPercentage = promoted ? 30 : 0
                    const blessPercentage    = (100 - ((8 * bless) + promotedPercentage)) / 100

                    return (penalty * blessPercentage).formatMoney(0, '.', '.')
                }

                return 0
            },

            getPrice (bless) {
                const level = parseInt(this.settings.level)

                if (level > 0) {

                    let price = 0

                    if (level <= 30) {
                        price = 2000
                    } else if (level > 30 && level < 120) {
                        price = 2000 + (200 * (level - 30))
                    } else {
                        price = 20000
                    }

                    if (bless == 'Blood of the Mountain' || bless == 'Heart of the Mountain') {
                        price = price * 1.3
                    }

                    return price

                }

                return 0
            }
        },
    }
</script>