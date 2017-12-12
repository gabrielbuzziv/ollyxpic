<template>
    <div class="equipments chooseable">
        <div class="form">
            <el-select v-model="newItem"
                       no-match-text="Item not found"
                       no-data-text="Items not found"
                       placeholder="Choose the Equipments"
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
            <div class="col-md-3" v-for="equipment, index in equipments">
                <div class="item">
                    <div class="thumb">
                        <img :src="image_path('item', equipment.item.id)" alt="">
                    </div>

                    <div class="name">{{ equipment.item.title }}</div>

                    <div class="amount">
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
        props: ['equipments'],

        data () {
            return {
                newItem: '',
                categories: []
            }
        },

        watch: {
            equipments () {
                this.$emit('update:equipments', this.equipments)
            }
        },

        methods: {
            getCategories () {
                services.getCategories([2, 3, 5, 8, 10, 25, 27, 28, 38, 41, 42, 44, 46])
                    .then(response => {
                        this.categories = response.data
                    })
            },

            getItems (category) {
                return category.items && category.items.length
                    ? category.items.filter(item => item.equipment)
                    : []
            },

            addItem () {
                if (this.newItem != null && this.newItem != '') {
                    this.equipments.push({ item: this.newItem, })
                    this.newItem = ''
                }
            },

            removeItem (index) {
                this.equipments.splice(index, 1)
            }
        },

        mounted () {
            this.getCategories()
        }
    }
</script>