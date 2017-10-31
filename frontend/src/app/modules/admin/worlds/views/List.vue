<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.worlds.create' }" class="btn btn-default">
                    <i class="mdi mdi-plus margin-right-5"></i>
                    Create
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'globe')" class="margin-right-10">
            Worlds
            <span>Manage Worlds</span>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="world in worlds">
                        <td>{{ world.name }}</td>
                        <td>{{ world.type }}</td>
                        <td class="text-right">
                            <router-link :to="{ name: 'admin.worlds.edit', params: { id: world.id } }" class="btn btn-xs">
                                <i class="mdi mdi-pencil-circle"></i>
                            </router-link>
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
                worlds: []
            }
        },

        methods: {
            load () {
                services.fetchWorlds()
                    .then(response => {
                        this.worlds = response.data
                    })
            },

//            remove (post) {
//                this.$confirm(`If you remove the post "${post.title}", you will not be able to recovery.`, 'Are you sure about this?', {
//                    cancelButtonText: 'Cancel',
//                    confirmButtonText: 'Yes, delete it',
//                    type: 'error',
//                }).then(() => {
//                    services.remove(post.id)
//                        .then(response => {
//                            this.$message.success(`The post "${post.title}" has been removed.`)
//                            this.load()
//                        })
//                })
//            }
        },

        mounted () {
            this.load()
        }
    }
</script>