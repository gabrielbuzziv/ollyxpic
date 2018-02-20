<template>
    <page-load id="imbuements">
        <page-title>
            <img :src="image_path_by_name('item', 'silencer claws')">
            <div class="title">
                <h2>Imbuements</h2>
                <span>Waste/Time</span>
            </div>
        </page-title>

        <panel v-if="selectedImbuements.length">
            <table class="table totals">
                <thead>
                    <tr>
                        <th>Imbuement</th>
                        <th>Material</th>
                        <th>Success Fee</th>
                        <th>Creation Fee</th>
                        <th>Total</th>
                        <th>Total/Hour</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="imbuement in selectedImbuements">
                        <td>
                            <div class="label" :class="getTierLabels(getTier(imbuement)).class">
                                {{ getTierLabels(getTier(imbuement)).label }}
                            </div>
                            {{ imbuement.title }}

                            <small class="save" v-html="getMaterial(imbuement).save" v-if="getMaterial(imbuement).save"></small>
                            <small class="market" v-else>Buy materials on market</small>
                        </td>
                        <td>{{ getMaterial(imbuement).price.format() }} gp</td>
                        <td>{{ getSuccessFee(imbuement).format() }} gp</td>
                        <td>{{ getCreationFee(imbuement).format() }} gp</td>
                        <td>{{ getImbuementTotal(imbuement).format() }} gp</td>
                        <td>{{ getHourValue(getImbuementTotal(imbuement)).format() }} gp/h</td>
                    </tr>

                    <tr class="total">
                        <td>
                            <div class="gold-token-input">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <img :src="image_path_by_name('item', 'gold token')"/>
                                    </div>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Gold Token Price"
                                           @input="updateToken"
                                           v-model="goldToken">
                                </div>

                                <div class="small helper-block">
                                    If you fill the gold token price, we will calculate if is worth buy materials using gold tokens to save money.
                                </div>
                            </div>
                        </td>
                        <td colspan="3"></td>
                        <td class="price">{{ getTotal().format() }} gps</td>
                        <td class="price">{{ getHourValue(getTotal()).format() }} gps/h</td>
                    </tr>
                </tbody>
            </table>
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
                        <th class="text-right">Success 100%</th>
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
                goldToken: ''
            }
        },

        computed: {
            selectedImbuements () {
                return this.imbuements.filter(imbuement => imbuement.amount > 0 && this.getTier(imbuement) > 0)
            },
        },

        methods: {
            isEmpty (value) {
                return value == '' || value == null
            },

            getTier (imbuement) {
                if (! this.isEmpty(imbuement.powerful)
                    && ! this.isEmpty(imbuement.intricate)
                    && ! this.isEmpty(imbuement.basic))
                    return 3

                if (! this.isEmpty(imbuement.intricate)
                    && ! this.isEmpty(imbuement.basic))
                    return 2

                if (! this.isEmpty(imbuement.basic))
                    return 1
            },

            getMaterial (imbuement) {
                const amount = parseInt(imbuement.amount)
                const basic = this.getBasic(imbuement)
                const intricate = this.getIntricate(imbuement)
                const powerful = this.getPowerful(imbuement)
                let total = basic + intricate + powerful

                if (! this.isEmpty(this.goldToken)) {
                    const token = this.calculateGoldToken(imbuement, basic, intricate, powerful)
                    return total > token.total
                        ? { price: token.total, save: this.getSaveText(imbuement, token.tier) }
                        : { price: total }
                }

                return { price: total }
            },

            getBasic (imbuement) {
                return imbuement.items[0].amount * imbuement.basic
            },

            getIntricate (imbuement) {
                return imbuement.items[1].amount * imbuement.intricate
            },

            getPowerful (imbuement) {
                return imbuement.items[2].amount * imbuement.powerful
            },

            calculateGoldToken (imbuement, basic, intricate, powerful) {
                if (imbuement.title != 'Strike' && imbuement.title != 'Void' && imbuement.title != 'Vampirism') return false
                const token = parseInt(this.goldToken)
                const powerfulToken = 6 * token
                const intricateToken = 4 * token
                const basicToken = 2 * token

                switch (this.getTier(imbuement)) {
                    case 1:
                        return basic > basicToken
                            ? { tier: 1, total: basicToken }
                            : false
                    case 2:
                        return intricate + basic > intricateToken
                            ? { tier: 2, total: intricateToken }
                            : basic > basicToken
                                ? { tier: 1, total: basicToken + intricate }
                                : false
                    case 3:
                        return (powerful + intricate + basic) > powerfulToken
                            ? { tier: 3, total: powerfulToken }
                            : intricate + basic > intricateToken
                                ? { tier: 2, total: intricateToken + powerful }
                                : basic > basicToken
                                    ? { tier: 1, total: basicToken + intricate }
                                    : false
                }

                return 0
            },

            getSaveText (imbuement, tier) {
                const basic = imbuement.items[0].item
                const intricate = imbuement.items[1].item
                const powerful = imbuement.items[2].item

                switch (tier) {
                    case 1:
                        return `Buy <b>${basic.title}</b> using 2 gold tokens.`
                    case 2:
                        return `Buy <b>${basic.title}</b> and <b>${intricate.title}</b> using 4 gold tokens.`
                    case 3:
                        return `Buy <b>${basic.title}</b>, <b>${intricate.title}</b> and <b>${powerful.title}</b> using 6 gold tokens.`
                }
            },

            getSuccessFee (imbuement) {
                if (! imbuement.success) return 0

                const amount = parseInt(imbuement.amount)

                switch (this.getTier(imbuement)) {
                    case 3:
                        return 50000 * amount
                    case 2:
                        return 30000 * amount
                    case 1:
                        return 10000 * amount
                }
            },

            getCreationFee (imbuement) {
                const amount = parseInt(imbuement.amount)

                switch (this.getTier(imbuement)) {
                    case 3:
                        return 100000 * amount
                    case 2:
                        return 25000 * amount
                    case 1:
                        return 5000 * amount
                }
            },

            getImbuementTotal (imbuement) {
                return this.getMaterial(imbuement).price + this.getSuccessFee(imbuement) + this.getCreationFee(imbuement)
            },

            getTotal () {
                return this.selectedImbuements.reduce((carry, imbuement) => carry + this.getImbuementTotal(imbuement), 0)
            },

            getTierLabels (tier) {
                switch (tier) {
                    case 1:
                        return {
                            label: 'Basic',
                            class: 'label-primary'
                        }
                    case 2:
                        return {
                            label: 'Intricate',
                            class: 'label-danger'
                        }
                    case 3:
                        return {
                            label: 'Powerful',
                            class: 'label-success'
                        }
                }
            },

            getHourValue (value) {
                return value / 20
            },

            updateToken () {
                localStorage.setItem(`imbuement.token`, this.goldToken)
            }
        },

        mounted () {
            services.fecthImbuements()
                .then(response => {
                    this.goldToken = localStorage.getItem(`imbuement.token`) || ''

                    this.imbuements = response.data.map(imbuement => {
                        const amount = localStorage.getItem(`imbuement.${imbuement.id}.amount`) || 0
                        const basic = localStorage.getItem(`imbuement.${imbuement.id}.basic`) || ''
                        const intricate = localStorage.getItem(`imbuement.${imbuement.id}.intricate`) || ''
                        const powerful = localStorage.getItem(`imbuement.${imbuement.id}.powerful`) || ''
                        const success = localStorage.getItem(`imbuement.${imbuement.id}.success`) || false

                        return {
                            ...imbuement,
                            amount,
                            basic,
                            intricate,
                            powerful,
                            success: success == 'true' ? true : false
                        }
                    })
                })
        }
    }
</script>