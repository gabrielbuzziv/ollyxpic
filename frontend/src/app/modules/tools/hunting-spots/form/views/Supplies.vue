<template>
    <div class="supplies chooseable">
        <div class="form">
            <el-select v-model="newItem"
                       no-match-text="Item not found"
                       no-data-text="Items not found"
                       placeholder="Choose the Supply"
                       value-key="id"
                       popper-class="item-popper"
                       default-first-option
                       filterable>
                <el-option-group v-for="category in categories" :key="category.id" :label="category.title">
                    <el-option v-for="item in getItems(category)" :value="item" :label="item.title" :key="item.id">
                        <div class="thumb">
                            <img :src="image_path('item', item.id)">
                        </div>

                        <div class="name">{{ item.title }}</div>
                    </el-option>
                </el-option-group>
            </el-select>

            <button class="btn" @click.prevent="addItem">
                <i class="mdi mdi-plus-circle margin-right-5"></i>
                Add
            </button>
        </div>

        <div class="row chooseable-items">
            <div class="col-md-3" v-for="supply, index in supplies">
                <div class="item">
                    <div class="thumb">
                        <img :src="image_path('item', supply.item.id)" alt="">
                    </div>

                    <div class="name">{{ supply.item.title }}</div>

                    <div class="amount">
                        <el-input-number v-model="supply.amount"
                                         :min="0"
                                         :step="getAmountStep(supply.item.category_id)"></el-input-number>

                        <a href="#" class="btn-remove" @click.prevent="removeItem(index)">
                            remove
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import services from '../services'

    export default {
        props: ['supplies'],

        data () {
            return {
                newItem: '',
                categories: []
            }
        },

        watch: {
            supplies () {
                this.$emit('update:supplies', this.supplies)
            }
        },

        methods: {
            getCategories () {
                services.getCategories([1, 2, 4, 30, 38])
                    .then(response => {
                        this.categories = response.data
                    })
            },

            getItems (category) {
                return category.items && category.items.length
                    ? category.items.filter(item => item.supply)
                    : []
            },

            getAmountStep (category) {
                switch (category) {
                    case 1:
                    case 4:
                    case 30:
                        return 100
                    default:
                        return 1
                }
            },

            addItem () {
                if (this.newItem != null && this.newItem != '') {
                    this.supplies.push({
                        item: this.newItem,
                        amount: this.getAmountStep(this.newItem.category_id)
                    })
                    this.newItem = ''
                }
            },

            removeItem (index) {
                this.supplies.splice(index, 1)
            }
        },

        mounted () {
            this.getCategories()
        }
    }
</script>