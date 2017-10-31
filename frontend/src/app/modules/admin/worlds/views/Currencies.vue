<template>
    <table class="table margin-bottom-0">
        <thead>
            <tr>
                <th>Buy</th>
                <th>Sell</th>
                <th>Date</th>
            </tr>

            <tr>
                <th>
                    <input type="text" class="form-control" placeholder="Buy" v-model="currency.buy" @keydown.13="add">
                </th>
                <th>
                    <input type="text" class="form-control" placeholder="Sell" v-model="currency.sell" @keydown.13="add">
                </th>
                <th>
                    <button class="btn btn-success btn-sm" @click.prevent="add">
                        <i class="mdi mdi-check-circle margin-right-5"></i>
                        Add
                    </button>
                </th>
            </tr>
        </thead>

        <tbody v-if="world.currencies && world.currencies.length">
            <tr v-for="currency in world.currencies">
                <td>{{ currency.buy }} gp</td>
                <td>{{ currency.sell }} gp</td>
                <td>{{ currency.created_at }}</td>
            </tr>
        </tbody>

        <tbody v-else>
            <tr>
                <td colspan="3" class="text-center">
                    No currencies at the moment.
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import services from '../services'

    export default {
        props: ['world'],

        data () {
            return {
                currency: {
                    buy: '',
                    sell: ''
                }
            }
        },

        methods: {
            add () {
                if ((this.currency.buy == null || this.currency.buy == '') || (this.currency.sell == null || this.currency.sell == '')) {
                    this.$message.error('Add buy and sell currency values.')
                    return false
                }

                services.addCurrency(this.world.id, this.currency)
                    .then(response => {
                        this.$emit('updated')
                        this.$message.success('Currency added')
                        this.currency = { buy: '', sell: '' }
                    })
            }
        }
    }
</script>