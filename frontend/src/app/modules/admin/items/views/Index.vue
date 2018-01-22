<template>
    <page-load id="admin" class="items">
        <page-title>
            <img :src="image_path_by_name('item', 'Golden Boots')" class="margin-right-10">
            <div class="title">
                <h2>Items</h2>
                <span>Items categories and properties configuration</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-3">
                <panel title="Categories" class="sidemenu">
                    <page-load page-class="no-padding" :loading="loadingCategories">
                        <ul>
                            <li v-for="category in categories" :key="category.id">
                                <router-link :to="{ name: 'admin.items.category', params: { category: category.id } }">
                                    {{ category.title }}
                                </router-link>
                            </li>
                        </ul>
                    </page-load>
                </panel>
            </div>

            <div class="col-md-9">
                <panel :title="category.title">
                    <page-load page-class="no-padding" :loading="loadingItems">
                        <table class="table table-items">
                            <thead>
                                <tr>
                                    <th width="80"></th>
                                    <th>Item name</th>
                                    <th>
                                        Usable
                                        <small>Damage Protection</small>
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>

                            <item :item="item" v-for="item in items" :key="item.id" @updated="load"></item>
                        </table>
                    </page-load>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script>
    import Item from './partials/Item'
    import services from '../services'

    export default {
        components: { Item },

        data () {
            return {
                loadingCategories: true,
                loadingItems: true
            }
        },

        computed: {
            items () {
                return this.$store.getters['items/GET_ITEMS']
            },

            categories () {
                return this.$store.getters['items/GET_CATEGORIES']
            },

            category () {
                const index = this.categories.map(category => category.id).indexOf(this.$route.params.category)
                return {}
            }
        },

        watch: {
            '$route.params.category' () {
                this.load()
            }
        },

        methods: {
            load () {
                this.loadingItems = true
                this.$store.dispatch('items/FETCH_ITEMS', this.$route.params.category)
                    .then(() => this.loadingItems = false)
                    .catch(() => this.loadingItems = false)
            },
        },

        mounted () {
            this.$store.dispatch('items/FETCH_CATEGORIES')
                .then(() => {
                    this.loadingCategories = false
                    this.load()
                })
                .catch(() => this.loadingCategories = false)
        }
    }
</script>