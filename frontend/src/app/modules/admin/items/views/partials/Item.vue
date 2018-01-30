<template>
    <tbody>
        <tr>
            <td>
                <img :src="image_path('item', item.id)" alt="">
            </td>

            <td>
                {{ item.title }}
            </td>

            <td>
                <el-checkbox :checked="!! item.usable" @change="toggleUsable"></el-checkbox>
            </td>

            <td class="text-right" width="160">
                <button class="btn btn-xs" @click.prevent="toggleDetails">
                    <i class="mdi mdi-information"></i>
                </button>

                <button class="btn btn-xs" @click.prevent="toggleEdit">
                    <i class="mdi mdi-pencil-circle"></i>
                </button>

                <button class="btn btn-xs" @click.prevente="remove">
                    <i class="mdi mdi-close-circle"></i>
                </button>
            </td>
        </tr>

        <item-details :item="item" :show="showDetails" />
        <item-edit :item="item" :show="showEdit" />
    </tbody>
</template>

<script>
    import ItemDetails from './Details'
    import ItemEdit from './Edit'
    import services from '../../services'

    export default {
        props: ['item'],

        components: { ItemDetails, ItemEdit },

        data () {
            return {
                showDetails: false,
                showEdit: false
            }
        },

        methods: {
            toggleDetails () {
                this.showDetails = ! this.showDetails
                this.showEdit = false
            },

            toggleEdit () {
                this.showEdit = ! this.showEdit
                this.showDetails = false
            },

            toggleUsable () {
                this.$store.dispatch('items/TOGGLE_USABLE', this.item.id)
                    .then(() => {
                        this.$store.dispatch('items/FETCH_ITEMS', this.item.category.id)
                    })
            },

            remove () {
                this.$confirm(`If you remove this item, will not be recoverable.`, 'Are you sure about this?', {
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, remove it',
                    type: 'error',
                }).then(() => {
                    services.remove(this.item.id)
                        .then(() => {
                            this.$message.success(`The item ${this.item.title} has been removed.`)
                            this.$emit('updated')
                        })
                })
            }
        }
    }
</script>