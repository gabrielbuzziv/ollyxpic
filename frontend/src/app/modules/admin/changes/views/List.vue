<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.changes.create' }" class="btn btn-success btn-rounded">
                    <i class="mdi mdi-plus-circle margin-right-10"></i>
                    Add new
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'scroll of heroic deeds')" class="margin-right-10">
            <div class="title">
                <h2>Change Log</h2>
                <span>Manage changes</span>
            </div>
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
                        <router-link :to="{ name: 'admin.changes.edit', params: { id: change.id } }" class="btn btn-xs"
                                     tag="button">
                            <i class="mdi mdi-pencil-circle"></i>
                        </router-link>

                        <button class="btn btn-xs" @click.prevent="remove(change)">
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