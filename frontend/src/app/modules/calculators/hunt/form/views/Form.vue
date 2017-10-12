<template>
    <page-load>
        <page-title>
            <img :src="image_path('item', 296)" class="margin-right-10">
            Hunt
            <span>Profit/Waste</span>
        </page-title>

        <div class="col-md-12">
            <panel>
                <table class="table margin-bottom-0">
                    <thead>
                        <tr>
                            <th width="80"></th>
                            <th>Name</th>
                            <th>Waste</th>
                            <th width="200" class="text-center">Profit</th>
                            <th width="200" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="waste, index in wastes">
                            <td>
                                <button class="btn-link" @click.prevent="addWaste">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="removeWaste(index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                            <td>
                                <form-input :data="waste.character" v-model="waste.character" placeholder="Character" />
                            </td>
                            <td>
                                <form-input :data="waste.value" v-model="waste.value" placeholder="Waste in gps" />
                            </td>
                            <td class="text-center">
                                {{ individualProfit.formatMoney(0, '.', '.') }} gp
                            </td>
                            <td class="text-right">
                                {{ getTotal(waste).formatMoney(0, '.', '.') }} gp
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center">Waste</td>
                            <td class="text-right">{{ totalWaste.formatMoney(0, '.', '.') }} gp</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center">Loot</td>
                            <td class="text-right">
                                <form-input :data="loot" v-model="loot" placeholder="Total loot in gp" class="text-right" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center">Profit</td>
                            <td class="text-right">{{ profit.formatMoney(0, '.', '.') }} gp</td>
                        </tr>
                    </tbody>
                </table>
            </panel>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import SuppliesCalculator from './SuppliesCalculator'
    import services from '../services'

    export default {
        components: { SuppliesCalculator },

        data () {
            return {
                loot: null,
                wastes: [{ character: '', value: '' }]
            }
        },

        computed: {
            totalWaste () {
                return this.wastes.reduce((carry, waste) => {
                    const value = parseInt(waste.value) || 0
                    return carry + value
                }, 0)
            },

            profit () {
                return this.loot - this.totalWaste
            },

            individualProfit () {
                return this.profit / this.wastes.length
            }
        },

        methods: {
            getTotal(waste) {
                return this.individualProfit + parseInt(waste.value)
            },

            addWaste () {
                this.wastes.push({ character: '', value: '' })
            },

            removeWaste (index) {
                this.wastes.splice(index, 1)
            }
        },
    }
</script>