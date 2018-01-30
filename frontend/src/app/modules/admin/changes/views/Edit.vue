<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.changes.list' }" class="btn btn-rounded" exact>
                    <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'scroll of heroic deeds')" class="margin-right-10">
            <div class="title">
                <h2>Change Log: {{ change.title }}</h2>
                <span>Manage changes</span>
            </div>
        </page-title>

        <panel>
            <changes-form :action="`/admin/changes/${$route.params.id}`" method="patch" :data="change" v-if="! loading" />
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