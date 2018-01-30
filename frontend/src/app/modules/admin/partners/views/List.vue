<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.partners.new' }" tag="button" class="btn btn-success btn-rounded">
                    <i class="mdi mdi-plus-circle margin-right-10"></i>
                    Add new
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Key of Numerous Locks')" class="margin-right-10">
            <div class="title">
                <h2>Partners</h2>
                <span>Manage Partners</span>
            </div>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="partner in partners">
                        <td>{{ partner.name }}</td>
                        <td class="text-right">
                            <router-link :to="{ name: 'admin.partners.edit', params: { id: partner.id } }" class="btn btn-xs">
                                <i class="mdi mdi-pencil-circle"></i>
                            </router-link>

                            <button class="btn btn-xs" @click.prevent="remove(partner)">
                                <i class="mdi mdi-close-circle"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                loading: false,
                partners: [],
            }
        },

        methods: {
            load () {
                services.fetchPartners()
                    .then(response => this.partners = response.data)
            },

            remove (partner) {
                this.$confirm(`You're sure to remove this partner?`, 'Are you sure about this?', {
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, remove it',
                    type: 'error',
                }).then(() => {
                    services.remove(partner.id)
                        .then(() => {
                            this.$message.success(`The partner "${partner.name}" has been removed.`)
                            this.load()
                        })
                })
            }
        },

        mounted () {
            this.load()
        }
    }
</script>