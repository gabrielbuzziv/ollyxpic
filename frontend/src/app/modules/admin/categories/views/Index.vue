<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'Signed Contract')" class="margin-right-10">
            <div class="title">
                <h2>Categories</h2>
                <span>Manage Categories</span>
            </div>
        </page-title>

        <panel>
            <page-load page-class="no-padding" :loading="loading">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="80"></th>
                            <th>Category name</th>
                            <th>
                                Usable
                                <small>Damage Protection</small>
                            </th>
                            <th class="text-center">Slot</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <category :category="category" v-for="category in categories" :key="category.id"></category>
                    </tbody>
                </table>
            </page-load>
        </panel>
    </page-load>
</template>

<script>
    import Category from './partials/Category'

    export default {
        components: { Category },

        data () {
            return {
                loading: true,
            }
        },

        computed: {
            categories () {
                return this.$store.getters['categories/GET_CATEGORIES']
            }
        },

        mounted () {
            this.$store.dispatch('categories/FETCH_CATEGORIES')
                .then(() => this.loading = false)
                .catch(() => this.loading = false)
        }
    }
</script>