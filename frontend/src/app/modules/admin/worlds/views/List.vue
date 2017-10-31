<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.imbuements.create' }" class="btn btn-default">
                    <i class="mdi mdi-plus margin-right-5"></i>
                    Create
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'silencer claws')" class="margin-right-10">
            Imbuements
            <span>Manage Imbuements</span>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Name</th>
                        <th class="text-cemter">Basic</th>
                        <th class="text-cemter">Intricate</th>
                        <th class="text-cemter">Powerful</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="imbuement in imbuements">
                        <td>{{ imbuement.title }}</td>
                        <td>{{ imbuement.name }}</td>
                        <td class="text-cemter">
                            <img :src="image_path('item', getItemByTier(imbuement.items, 1).item.id)"
                                 :title="`${getItemByTier(imbuement.items, 1).amount} ${getItemByTier(imbuement.items, 1).item.title}`"
                                 v-if="getItemByTier(imbuement.items, 1).item.id">
                        </td>
                        <td class="text-cemter">
                            <img :src="image_path('item', getItemByTier(imbuement.items, 2).item.id)"
                                 :title="`${getItemByTier(imbuement.items, 2).amount} ${getItemByTier(imbuement.items, 2).item.title}`"
                                 v-if="getItemByTier(imbuement.items, 2).item.id">
                        </td>
                        <td class="text-cemter">
                            <img :src="image_path('item', getItemByTier(imbuement.items, 3).item.id)"
                                 :title="`${getItemByTier(imbuement.items, 3).amount} ${getItemByTier(imbuement.items, 3).item.title}`"
                                 v-if="getItemByTier(imbuement.items, 3).item.id">
                        </td>
                        <td class="text-right">
                            <router-link :to="{ name: 'admin.imbuements.edit', params: { id: imbuement.id } }" class="btn btn-xs">
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
                imbuements: []
            }
        },

        methods: {
            load () {
                services.fetchImbuements()
                    .then(response => {
                        this.imbuements = response.data
                    })
            },

            getItemByTier (items, tier) {
                return items.filter(item => item.tier == tier)[0]
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