<template>
    <page-load id="currencies">
        <page-title>
            <img :src="image_path_by_name('item', 'tibia coins')" class="margin-right-5">
            Tibia Currency
            <span class="margin-left-45">Stock Exchange</span>
        </page-title>


        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>World</th>
                        <th>World Type</th>
                        <th>Buy Currency</th>
                        <th>Sell Currency</th>
                        <th>Last Update</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="world in worlds">
                        <td>{{ world.name }}</td>
                        <td>{{ world.type }}</td>
                        <td>
                            {{ getLastCurrency(world).buy }} gp

                            <span v-if="world.currencies.length > 1">
                                <span class="currency-increase" v-if="compareLastTwoCurrencies(world, 'buy') > 0">
                                    <i class="mdi mdi-menu-up"></i>
                                    {{ compareLastTwoCurrencies(world, 'buy').toFixed(2) }} %
                                </span>

                                <span class="currency-decrease" v-else>
                                    <i class="mdi mdi-menu-down"></i>
                                    {{ compareLastTwoCurrencies(world, 'buy').toFixed(2) }} %
                                </span>
                            </span>
                        </td>
                        <td>
                            {{ getLastCurrency(world).sell }} gp

                            <span v-if="world.currencies.length > 1">
                                <span class="currency-increase" v-if="compareLastTwoCurrencies(world, 'sell') > 0">
                                    <i class="mdi mdi-menu-up"></i>
                                    {{ compareLastTwoCurrencies(world, 'sell').toFixed(2) }} %
                                </span>

                                <span class="currency-decrease" v-else>
                                    <i class="mdi mdi-menu-down"></i>
                                    {{ compareLastTwoCurrencies(world, 'sell').toFixed(2) }} %
                                </span>
                            </span>
                        </td>
                        <td>{{ getLastCurrency(world).created_at }}</td>
                        <td class="text-right">
                            <router-link :to="{ name: 'calculators.currencies.world', params: { id: world.id } }"
                                         class="btn btn-xs"
                                         title="All currencies">
                                <i class="mdi mdi-chart-areaspline"></i>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                worlds: []
            }
        },

        methods: {
            getLastCurrency (world) {
                return world.currencies[0]
            },

            compareLastTwoCurrencies (world, currency) {
                if (world.currencies.length > 1) {
                    const last = world.currencies[0]
                    const penult = world.currencies[1]

                    return (100 - ((penult[currency] * 100) / last[currency]))
                }
            }
        },

        mounted () {
            services.fetchWorlds()
                .then(response => {
                    this.worlds = response.data
                })
        }
    }
</script>