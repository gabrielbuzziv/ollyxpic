<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'great shield')" class="margin-right-10">
            Damage Protection
            <span>Items categories and properties configuration</span>
        </page-title>

        <div class="row">
            <div class="col-md-3">
                <panel title="Categories">
                    <ul>
                        <li v-for="category in categories">
                            <a href="#" @click.prevent="showItemsFromCategory(category)">
                                {{ category.name }}
                            </a>
                        </li>
                    </ul>
                </panel>
            </div>

            <div class="col-md-9">
                <panel :title="title">
                    <page-load page-class="no-padding" :loading="loading">
                        <div v-if="items.length">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th class="text-center">Use on damage protection</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="item in items">
                                        <td>
                                            <img :src="image_path('item', item.id)" alt="">
                                        </td>
                                        <td>{{ item.title }}</td>
                                        <td class="text-center">
                                            <usable-in-category :item="item" :category="category" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else>
                            <em>Choose a category</em>
                        </div>
                    </page-load>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script>
    import UsableInCategory from './partials/UsableInCategory'
    import services from '../services'
    import { isEmpty } from 'lodash'

    export default {
        components: { UsableInCategory },

        data () {
            return {
                items: [],
                category: '',
                categories: [],
                loading: false,
            }
        },

        computed: {
            title () {
                return ! isEmpty(this.category) ? this.category.name : 'Items'
            }
        },

        methods: {
            load () {
                services.getCategories()
                    .then(response => {
                        this.categories = response.data
                    })
            },

            loadItems (category) {
                return services.getItemsByCategory(category.categories)
                    .then(response => {
                        this.items = response.data
                        this.category = category

                        return response
                    })
            },

            showItemsFromCategory (category) {
                this.loading = true
                this.loadItems(category)
                    .then(response => this.loading = false)
                    .catch(() => this.loading = false)
            }
        },

        mounted () {
            this.load()

            this.$root.$on('load::items', category => this.loadItems(category))
        },

        beforeDestroy () {
            this.$root.$off('load::items')
        }
    }
</script>