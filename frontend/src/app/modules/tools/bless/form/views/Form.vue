<template>
    <page-load id="blessings">
        <page-title>
            <img :src="image_path_by_name('item', 'spiritual charm')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Blessing</h2>
                <span>Penalty</span>
            </div>
        </page-title>

        <div class="row">


	    <div class="col-md-12">
		<panel>
	<center><b>This calculator shows you how much experience you will loose with blessings.</b></center>
		</panel>
	    </div>


            <div class="col-md-12">
                <panel class="form">
                    <div class="row">
                        <div class="col-md-6">
                            <form-input class="level" v-model="settings.level" placeholder="Level"/>
                        </div>

                        <div class="col-md-6">
                            <el-checkbox v-model="settings.promoted">Promotion</el-checkbox>
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
                                <th class="text-center">Experience</th>
                                <th class="text-center">Equipment</th>
                                <th class="text-center">Container</th>
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
                                <td class="text-center">{{ getEquipmentLoss(bless) }} %</td>
                                <td class="text-center">{{ getContainerLoss(bless) }} %</td>
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
                                <th class="text-left">Sum</th>
                                <th class="text-center">Bless</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="bless in prices">
                                <td class="text-left">
                                    <el-checkbox v-model="bless.sum" style="line-height: 0;"></el-checkbox>
                                </td>
                                <td class="text-center">{{ bless.name }}</td>
                                <td class="text-right">{{ getPrice(bless.name).formatMoney(0, '.', '.') }} gp</td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center">Total</td>
                                <td class="text-right">{{ totalBless.formatMoney(0, '.', '.') }} gp</td>
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
                    { sum: true, name: 'Spiritual Shielding' },
                    { sum: true, name: 'Embrace of TIbia' },
                    { sum: true, name: 'Fire of the Suns' },
                    { sum: true, name: 'Wisdom of Solitude' },
                    { sum: true, name: 'Spark of the Phoenix' },
                    { sum: true, name: 'Blood of the Mountain' },
                    { sum: true, name: 'Heart of the Mountain' },
                    { sum: false, name: 'Twist of Fate' },
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
                    return bless.sum ? carry + parseInt(this.getPrice(bless.name)) : carry
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

            getEquipmentLoss (bless) {
                switch (bless) {
                    case 0:
                        return 10
                    case 1:
                        return 7
                    case 2:
                        return 4.5
                    case 3:
                        return 2.5
                    case 4:
                        return 1
                    default:
                        return 0
                }
            },

            getContainerLoss (bless) {
                switch (bless) {
                    case 0:
                        return 100
                    case 1:
                        return 70
                    case 2:
                        return 45
                    case 3:
                        return 25
                    case 4:
                        return 10
                    default:
                        return 0
                }
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
