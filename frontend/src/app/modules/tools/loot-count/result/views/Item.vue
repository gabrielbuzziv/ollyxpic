<template>
    <div class="col-lg-3 col-md-4">
        <panel class="item">
            <button class="btn btn-blank btn-remove" @click.prevent="remove">
                <i class="mdi mdi-close"></i>
            </button>

            <header>
                <img :src="image_path('item', item.id)" class="margin-right-15">
                <span>
                    <span class="name">{{ item.title }}</span>
                    <span class="total">{{ total }} gp</span>
                </span>
            </header>

            <section>
                <el-tooltip content="Amount/Quantity" placement="bottom-start" v-if="owner">
                    <input type="text" class="quantity form-control text-center" v-model="info.quantity"
                           @input="update">
                </el-tooltip>

                <span class="quantityLabel" v-else>
                    <span>Amount</span>
                    <b>{{ info.quantity }}x</b>
                </span>

                <el-tooltip content="Unit Price" placement="bottom-start" v-if="owner">
                    <el-input-number v-model="info.unit_price"
                                     class="price"
                                     @change="update"
                                     :min="0"
                                     :step="1000">
                    </el-input-number>
                </el-tooltip>

                <span class="priceLabel" v-else>
                    <span>Price</span>
                    <b>{{ info.unit_price.format() }} gp</b>
                </span>
            </section>
        </panel>
    </div>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        props: ['info', 'hunt', 'password'],

        computed: {
            item () {
                return this.info.data
            },

            total () {
                return (this.info.quantity * this.info.unit_price).format()
            },

            owner () {
                return this.password ? this.password == this.hunt.password : false
            }
        },

        methods: {
            update: debounce(function () {
                services.updateItem(this.hunt.id, this.info, this.password)
                    .then(response => this.$message.success('Item has been updated.'))
            }, 300),

            remove () {
                this.$confirm('Are you sure you want to remove this item?', 'Remove item from Loot', {
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                    type: 'error'
                }).then(() => {
                    const item = this.info
                    this.info.quantity = 0

                    services.updateItem(this.hunt.id, this.info, this.password)
                        .then(response => this.$message.success('Item has been removed.'))

                    this.$emit('removed', item)
                })
            }
        },
    }
</script>