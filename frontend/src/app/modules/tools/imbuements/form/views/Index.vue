<template>
    <page-load id="imbuements">
        <page-title>
            <img :src="image_path_by_name('item', 'silencer claws')">
            <div class="title">
                <h2>Imbuements</h2>
                <span>Waste/Time</span>
            </div>
        </page-title>

        <div class="alert alert-info" v-if="isTokenWorth(imbuement)" v-for="imbuement in selectedImbuements">
            <h4>{{ imbuement.title }}</h4>
            <p v-html="getSuggestion(imbuement)"></p>
        </div>

        <!--<panel>-->
            <!--<table class="table">-->
                <!--<thead>-->
                    <!--<tr>-->
                        <!--<th>Total</th>-->
                        <!--<th>Total/Hour</th>-->
                        <!--<th>Time Used</th>-->
                        <!--<th>Value</th>-->
                    <!--</tr>-->
                <!--</thead>-->

                <!--<tbody>-->
                    <!--<tr>-->
                        <!--<td>-->
                            <!--Material + Protection + Fees-->
                        <!--</td>-->
                        <!--<td></td>-->
                        <!--<td></td>-->
                        <!--<td></td>-->
                    <!--</tr>-->
                <!--</tbody>-->
            <!--</table>-->
        <!--</panel>-->

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

        methods: {
            calculateMaterial (imbuement, tier) {
                let price = 0
                const amount = imbuement.items.filter(item => item.tier == tier)[0].amount
                switch (tier) {
                    case 1:
                        price = ! this.isEmpty(imbuement.basic) ? parseInt(imbuement.basic) : 0
                        return amount * imbuement.basic
                    case 2:
                        price = ! this.isEmpty(imbuement.intricate) ? parseInt(imbuement.intricate) : 0
                        return amount * imbuement.intricate
                    case 3:
                        price = ! this.isEmpty(imbuement.powerful) ? parseInt(imbuement.powerful) : 0
                        return amount * imbuement.powerful
                }
            },

            getSuggestion (imbuement) {
                const total = this.calculateImbuementTotal(imbuement)
                const save = (total.total - total.token.price).format()
                let items = ''

                switch (total.token.tier) {
                    case 'basic':
                        items  = `${imbuement.items[0].amount} ${imbuement.items[0].item.title}`
                        return `Save <b>${save} gps</b> by buying the basic items (${items}) using gold tokens.`
                    case 'intricate':
                        items  = `${imbuement.items[0].amount} ${imbuement.items[0].item.title} & ${imbuement.items[1].amount} ${imbuement.items[1].item.title}`
                        return `Save <b>${save} gps</b> by buying the intricate items (${items}) using gold tokens.`
                    case 'powerful':
                        items  = `${imbuement.items[0].amount} ${imbuement.items[0].item.title}
                        & ${imbuement.items[1].amount} ${imbuement.items[1].item.title}
                        & ${imbuement.items[2].amount} ${imbuement.items[2].item.title}`
                        return `Save <b>${save} gps</b> by buying the powerful items (${items}) using gold tokens.`
                }
            },

            calculateImbuementTotal (imbuement) {
                const token = this.goldToken ? this.compareGoldToken(imbuement) : 0
                const basic = this.calculateMaterial(imbuement, 1)
                const intricate = this.calculateMaterial(imbuement, 2)
                const powerful = this.calculateMaterial(imbuement, 3)
                const total = basic + intricate + powerful

                if (! this.isEmpty(imbuement.basic) && ! this.isEmpty(imbuement.intricate) && ! this.isEmpty(imbuement.powerful)) {
                    return { total, token }
                }

                if (! this.isEmpty(imbuement.basic) && ! this.isEmpty(imbuement.intricate)) {
                    return { total, token }
                }

                if (! this.isEmpty(imbuement.basic)) {
                    return { total, token }
                }
            },

            compareGoldToken (imbuement) {
                const basic = this.calculateMaterial(imbuement, 1)
                const intricate = this.calculateMaterial(imbuement, 2)
                const powerful = this.calculateMaterial(imbuement, 3)

                if (! this.isEmpty(imbuement.powerful)) {
                    if ((powerful + intricate + basic) > (this.goldToken * 6)) return { tier: 'powerful', price: this.goldToken * 6 }
                    if ((intricate + basic) > (this.goldToken * 4)) return { tier: 'intricate', price: (this.goldToken * 4) + powerful }
                    if ((basic) > (this.goldToken * 2)) return { tier: 'basic', price: (this.goldToken * 2) + intricate + powerful }
                }

                if (! this.isEmpty(imbuement.intricate)) {
                    if ((intricate + basic) > (this.goldToken * 4)) return { tier: 'intricate', price: (this.goldToken * 4) + powerful }
                    if ((basic) > (this.goldToken * 2)) return { tier: 'basic', price: (this.goldToken * 2) + intricate + powerful }
                }

                if (! this.isEmpty(imbuement.basic)) {
                    if ((basic) > (this.goldToken * 2)) return { tier: 'basic', price: (this.goldToken * 2) + intricate + powerful }
                }

                return 'material is worth'
            },

            isTokenWorth (imbuement) {
                const suggestion = this.calculateImbuementTotal(imbuement)
                const token = ! this.isEmpty(suggestion) ? suggestion.token : null
                return typeof token == 'object' && token != null ? true : false
            },

            isEmpty (value) {
                return value == '' || value == null
            }
        },

        mounted () {
            services.fecthImbuements()
                .then(response => {
                    this.imbuements = response.data.map(imbuement => {
                        return {
                            ...imbuement,
                            amount: 0,
                            basic: '',
                            intricate: '',
                            powerful: '',
                            protection: false
                        }
                    })
                })
        }
    }
</script>