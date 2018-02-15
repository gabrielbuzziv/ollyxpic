<template>
    <page-load id="imbuements">
        <page-title>
            <img :src="image_path_by_name('item', 'silencer claws')">
            <div class="title">
                <h2>Imbuements</h2>
                <span>Waste/Time</span>
            </div>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>Imbuement</th>
                        <th>Amount</th>
                        <th>Material</th>
                        <th>Protection Charm</th>
                        <th>Tax</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="imbuement in selectedImbuements">
                        <td>{{ imbuement.title }}</td>
                        <td>{{ imbuement.amount }}</td>
                        <td></td>
                        <td>{{ imbuement.protection }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </panel>

        <panel>
            <input type="text" v-model="goldToken">
        </panel>

        <panel>
            <table class="table imbuements">
                <thead>
                    <tr>
                        <th>Imbuement</th>
                        <th>Amount</th>
                        <th>Basic</th>
                        <th>Intricate</th>
                        <th>Powerful</th>
                        <th>100%</th>
                    </tr>
                </thead>

                <tbody>
                    <imbuement :imbuement="imbuement"
                               :gold-token="goldToken"
                               :key="imbuement.id"
                               v-for="imbuement in imbuements"/>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import Imbuement from './Imbuement'
    import services from '../services'

    export default {
        components: { Imbuement },

        data () {
            return {
                imbuements: [],
                goldToken: 0
            }
        },

        computed: {
            selectedImbuements () {
                return this.imbuements.filter(imbuement => imbuement.amount > 0)
            }
        },

        mounted () {
            services.fecthImbuements()
                .then(response => {
                    this.imbuements = response.data.map(imbuement => {
                        return {
                            ...imbuement,
                            amount: 0,
                            basic: {
                                price: 0,
                                token: false
                            },
                            intricate: {
                                price: 0,
                                token: false
                            },
                            powerful: {
                                price: 0,
                                token: false
                            },
                            protection: false
                        }
                    })
                })
        }
    }
</script>