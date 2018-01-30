<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.worlds' }" class="btn btn-rounded" exact>
                    <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'globe')" class="margin-right-10">
            <div class="title">
                <h2>World: {{ world.name }}</h2>
                <span>Manage Worlds</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-4">
                <panel>
                    <world-form :action="`/admin/worlds/${$route.params.id}`" method="patch" :data="world" v-if="! loading" />
                </panel>
            </div>

            <div class="col-md-8">
                <panel>
                    <world-currencies :world="world" @updated="load"></world-currencies>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script>
    import WorldForm from './Form'
    import WorldCurrencies from './Currencies'
    import services from '../services'

    export default {
        components: { WorldForm, WorldCurrencies },

        data () {
            return {
                world: {
                    name: '',
                    type: ''
                },
                loading: true
            }
        },

        methods: {
            load () {
                services.find(this.$route.params.id)
                    .then(response => {
                        this.world = response.data
                        this.loading = false
                    })
                    .catch(() => this.loading = false)
            }
        },

        mounted () {
            this.load()
        }
    }
</script>