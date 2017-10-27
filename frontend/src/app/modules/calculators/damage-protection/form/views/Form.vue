<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path_by_name('item', 'great shield')" alt="">
            Damage
            <span>Protection</span>
        </page-title>

        <div class="row">
            <div class="col-md-2">

            </div>

            <div class="col-md-10">
                <ul class="tabs">
                    <tab-link :tab="category.slug" v-for="category in categories" :key="category.id">
                        <img :src="image_path_by_name('item', category.image)" alt="">
                    </tab-link>
                </ul>

                <tab-content class="items" :tab="category.slug" v-for="category in categories" :key="category.id" @active="loadItems(category)">
                    <div class="item" v-for="item in items" :key="item.id">
                        <img :src="image_path('item', item.id)" slot="reference">
                    </div>
                </tab-content>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'

    export default {
        data () {
            return {
                categories: [],
                items: []
            }
        },

        methods: {
            loadItems (category) {
                services.getItems(category.id)
                    .then(response => {
                        this.items = response.data
                    })
            }
        },

        mounted () {
            services.getCategories()
                .then(response => {
                    this.categories = response.data
                })
        }
    }
</script>
