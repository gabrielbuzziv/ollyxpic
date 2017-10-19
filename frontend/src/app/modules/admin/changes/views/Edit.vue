<template>
    <page-load>
        <page-title>
            <img :src="image_path('item', 357)" class="margin-right-10">
            Changes: {{ change.title }}
            <span>Edit change</span>
        </page-title>

        <panel>
            <changes-form :action="`/changes/${$route.params.id}`" method="patch" :data="change" v-if="! loading" />
        </panel>
    </page-load>
</template>

<script>
    import ChangesForm from './Form'
    import services from '../services'

    export default {
        components: { ChangesForm },

        data () {
            return {
                change: {
                    title: '',
                    body: '',
                    created_at: null
                },
                loading: true
            }
        },

        mounted () {
            services.find(this.$route.params.id)
                .then(response => {
                    this.change = response.data
                    this.loading = false
                })
                .catch(() => this.loading = false)
        }
    }
</script>