<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.changes.create' }" class="btn btn-default">
                    <i class="mdi mdi-plus margin-right-5"></i>
                    Create
                </router-link>
            </div>

            <img :src="image_path('item', 357)" class="margin-right-10">
            Change Log
            <span>Manage changes</span>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="change in changes">
                    <td>{{ change.title }}</td>
                    <td>{{ change.author.name }}</td>
                    <td>{{ change.created_at }}</td>
                    <td class="text-right">
                        <button class="btn btn-sm">
                            <i class="mdi mdi-eye"></i>
                        </button>

                        <router-link :to="{ name: 'admin.changes.edit', params: { id: change.id } }" class="btn btn-sm"
                                     tag="button">
                            <i class="mdi mdi-pencil"></i>
                        </router-link>

                        <button class="btn btn-sm" @click.prevent="remove(change)">
                            <i class="mdi mdi-delete"></i>
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
                changes: []
            }
        },

        methods: {
            load () {
                services.fetchChanges()
                    .then(response => {
                        this.changes = response.data
                    })
            },

            remove (change) {
                this.$confirm(`If you remove the change "${change.title}", you will not be able to recovery.`, 'Are you sure about this?', {
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, delete it',
                    type: 'error',
                }).then(() => {
                    services.remove(change.id)
                        .then(response => {
                            this.$message.success(`The change "${change.title}" has been removed.`)
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