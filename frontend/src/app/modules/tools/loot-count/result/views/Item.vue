<template>
    <div class="col-md-3">
        <panel class="item">
            <button class="btn btn-blank btn-remove">
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
                <el-tooltip content="Amount/Quantity" placement="bottom-start">
                    <input type="text" class="quantity form-control text-center" v-model="info.quantity" @input="updateQuantity">
                </el-tooltip>

                <el-tooltip content="Unit Price" placement="bottom-start">
                    <el-input-number v-model="info.unit_price"
                                     class="price"
                                     @change="updatePrice"
                                     :min="0"
                                     :step="1000">
                    </el-input-number>
                </el-tooltip>
            </section>
        </panel>
    </div>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    export default {
        props: ['info'],

        computed: {
            item () {
                return this.info.data
            },

            total () {
                return (this.info.quantity * this.info.unit_price).format()
            }
        },

        methods: {
            updateQuantity () {

            },

            updatePrice () {
            }
        }
    }
</script>