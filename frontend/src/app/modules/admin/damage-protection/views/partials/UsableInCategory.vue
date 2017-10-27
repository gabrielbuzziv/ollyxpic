<template>
    <el-checkbox v-model="usable"></el-checkbox>
</template>

<script>
    import services from '../../services'

    export default {
        props: ['item', 'category'],

        computed: {
            usable: {
                get () {
                    return this.item.categories && this.item.categories.length ? true : false
                },

                set () {
                    services.toggleItemUsable(this.item.id, this.category.id)
                        .then(() => {
                            this.$root.$emit('load::items', this.category)
                        })
                }
            }
        }
    }
</script>