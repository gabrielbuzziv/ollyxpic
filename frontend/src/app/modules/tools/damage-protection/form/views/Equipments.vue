<template>
    <div class="equipments row">
        <!-- Equipment slots -->
        <div class="col-md-2" id="slots">
            <panel class="panel-slots">
                <div class="slots">
                    <div class="slot" :class="[index, slot.id ? 'active' : '']" v-for="slot, index in slots">
                        <slot-item :item.sync="slot" :index="index" @remove="removeSlot" />
                    </div>
                </div>
            </panel>
        </div>

        <!-- Equipments -->
        <div class="col-md-10" id="items">
            <el-tabs type="card" v-model="tabs" @tab-click="loadCategory">
                <el-tab-pane :name="`${index}`" :key="category.id" v-for="category, index in categories">
                    <span slot="label"><img :src="image_path_by_name('item', category.image)"/></span>

                    <panel class="items">
                        <div class="filter">
                            <input type="text" class="form-control"
                                   placeholder="Search ..."
                                   v-model="category.filter"
                                   @input="filterItemsFromCategory(category)">
                        </div>

                        <page-load class="no-padding row" :loading="category.loading">
                            <item :item.sync="item" :category="category" :key="item.id"
                                  v-for="item in category.items"
                                  @equiped="equipItem"/>
                        </page-load>
                    </panel>
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>

<script>
    import SlotItem from './SlotItem'
    import Item from './Item'
    import services from '../services'
    import { isEmpty } from 'lodash'

    export default {
        props: ['slots'],

        components: { SlotItem, Item },

        data () {
            return {
                tabs: '0',
                categories: [],
            }
        },

        computed: {
            categoriesId () {
                return this.categories.map(category => category.id)
            }
        },

        methods: {
            loadCategories () {
                services.getCategories()
                    .then(response => {
                        this.categories = response.data.map(category => {
                            return { ...category, items: [], loading: true, filter: '' }
                        })
                        this.categories.length ? this.loadItems(this.categories[0]) : null
                    })
            },

            loadItems (category) {
                services.getItems(category.id)
                    .then(response => {
                        const index = this.categoriesId.indexOf(category.id)
                        this.categories[index].items = response.data.map(item => {
                            return { ...item, visible: true, active: false, imbuements: [] }
                        })
                        this.categories[index].loading = false
                    })
                    .catch(() => {
                        const index = this.categoriesId.indexOf(category.id)
                        this.categories[index].loading = false
                    })
            },

            loadCategory (tab) {
                const index = tab.index
                const category = this.categories[index]
                const items = category.items

                ! items.length ? this.loadItems(category) : null
            },

            filterItemsFromCategory (category) {
                const filter = category.filter

                category.items.forEach(item => {
                    item.visible = item.title.toLowerCase().includes(filter)
                })
            },

            equipItem (response) {
                const category = this.getCategoryById(response.category).category
                const itemIndex = category.items.map(item => item.id).indexOf(response.item)
                const item = category.items[itemIndex]

                this.validate(category, item)

                this.categories[this.getCategoryById(response.category).index].items.forEach(item => item.active = item.id == response.item ? true : false)
                this.slots[response.slot] = item
            },

            getCategoryById (id) {
                const index = this.categoriesId.indexOf(id)
                return { category: this.categories[index], index }
            },

            validate (category, item) {
                const properties = item.properties
                const twoHanded = properties.map(property => property.property).indexOf('two-handed')
                if (twoHanded !== -1 && properties[twoHanded] && category.slot == 'weapon') {
                    this.slots['shield'] = []
                    this.categories[this.getCategoryById(41).index].items.forEach(item => item.active = false)
                    this.categories[this.getCategoryById(42).index].items.forEach(item => item.active = false)
                }

                if (category.slot == 'shield' && ! isEmpty(this.slots['weapon'])) {
                    this.slots['weapon'] = []
                    this.categories[this.getCategoryById(5).index].items.forEach(item => item.active = false)
                    this.categories[this.getCategoryById(10).index].items.forEach(item => item.active = false)
                    this.categories[this.getCategoryById(16).index].items.forEach(item => item.active = false)
                    this.categories[this.getCategoryById(39).index].items.forEach(item => item.active = false)
                    this.categories[this.getCategoryById(44).index].items.forEach(item => item.active = false)
                    this.categories[this.getCategoryById(49).index].items.forEach(item => item.active = false)
                }
            },

            removeSlot (slot) {
                this.slots[slot] = {}
            }
        },

        mounted () {
            this.loadCategories()
        }
    }
</script>