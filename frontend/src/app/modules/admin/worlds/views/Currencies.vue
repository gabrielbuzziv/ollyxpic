<template>
    <table class="table margin-bottom-0">
        <thead>
            <tr>
                <th>Buy</th>
                <th>Sell</th>
                <th>Date</th>
                <th width="80"></th>
            </tr>

            <tr>
                <th>
                    <input type="text" class="form-control" placeholder="Buy" v-model="currency.buy" @keydown.13="save">
                </th>
                <th>
                    <input type="text" class="form-control" placeholder="Sell" v-model="currency.sell" @keydown.13="save">
                </th>
                <th colspan="2">
                    <button class="btn btn-success btn-sm btn-block" @click.prevent="save">
                        <i class="mdi mdi-save margin-right-5"></i>
                        Save
                    </button>
                </th>
            </tr>
        </thead>

        <tbody v-if="world.currencies && world.currencies.length">
            <tr v-for="currency in world.currencies">
                <td>{{ currency.buy }} gp</td>
                <td>{{ currency.sell }} gp</td>
                <td>{{ currency.created_at }}</td>
                <td class="text-right">
                    <button class="btn btn-xs" @click.prevent="edit(currency)">
                        <i class="mdi mdi-pencil"></i>
                    </button>

                    <button class="btn btn-xs" @click.prevent="remove(currency)">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            </tr>
        </tbody>

        <tbody v-else>
            <tr>
                <td colspan="4" class="text-center">
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
                },
                editing: false
            }
        },

        methods: {
            save () {
                if ((this.currency.buy == null || this.currency.buy == '') || (this.currency.sell == null || this.currency.sell == '')) {
                    this.$message.error('Add buy and sell currency values.')
                    return false
                }

                if (this.editing) {
                    services.editCurrency(this.editing.id, this.currency)
                        .then(response => {
                            this.$emit('updated')
                            this.$message.success('Currency updated')
                            this.currency = { buy: '', sell: '' }
                            this.editing = false
                        })
                    return false
                }

                services.addCurrency(this.world.id, this.currency)
                    .then(response => {
                        this.$emit('updated')
                        this.$message.success('Currency added')
                        this.currency = { buy: '', sell: '' }
                    })
            },

            edit (currency) {
                this.currency = { buy: currency.buy, sell: currency.sell }
                this.editing = currency
            },

            remove (currency) {
                this.$confirm(`If you remove thIS currency you will not be able to recovery.`, 'Are you sure about this?', {
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, remove it',
                    type: 'error',
                }).then(() => {
                    services.remove(currency.id)
                        .then(response => {
                            this.$message.success(`The currency has been removed.`)
                            this.$emit('updated')
                        })
                })
            }
        }
    }
</script>