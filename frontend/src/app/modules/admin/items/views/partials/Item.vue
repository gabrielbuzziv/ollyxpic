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

            <td class="text-right">
                <button class="btn btn-xs" @click.prevente="remove">
                    <i class="mdi mdi-delete margin-right-5"></i>
                    Remove
                </button>

                <button class="btn btn-xs" @click.prevent="toggleDetails">
                    <i class="mdi mdi-chevron-up" v-if="showDetails"></i>
                    <i class="mdi mdi-chevron-down" v-else></i>
                </button>
            </td>
        </tr>

        <tr class="details" v-if="showDetails">
            <td colspan="4">
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <h4>Properties</h4>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="data">
                                    <b>Weight</b>
                                    <span>{{ item.capacity }} oz</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="data">
                                    <b>Stackable</b>
                                    <span>
                                        <i class="mdi mdi-check-circle" v-if="item.stackable"></i>
                                        <i class="mdi mdi-close-circle" v-else></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4" v-for="property in item.properties">
                                <div class="data">
                                    <b>{{ property.property }}</b>
                                    <span>{{ getPropertyValue(property) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 margin-top-10" v-if="item.sells.length">
                        <h4>Sell for</h4>

                        <div class="row">
                            <div class="col-md-3" v-for="sells in item.sells">
                                <div class="data">
                                    <b>{{ sells.npc.name }}</b>
                                    <span>{{ sells.value }} gp</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 margin-top-10" v-if="item.buys.length">
                        <h4>Buy for</h4>

                        <div class="row">
                            <div class="col-md-3" v-for="buys in item.buys">
                                <div class="data">
                                    <b>{{ buys.npc.name }}</b>
                                    <span>{{ buys.value }} gp</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script>
    import services from '../../services'

    export default {
        props: ['item'],

        data () {
            return {
                showDetails: false
            }
        },

        methods: {
            toggleDetails () {
                this.showDetails = ! this.showDetails
            },

            toggleUsable () {
                this.$store.dispatch('items/TOGGLE_USABLE', this.item.id)
                    .then(() => {
                        this.$store.dispatch('items/FETCH_ITEMS', this.item.category.id)
                    })
            },

            getPropertyValue (property) {
                if (property.property == 'Voc') {
                    return property.value.replace('k', 'Knight')
                        .replace('s', 'Sorcerer')
                        .replace('d', 'Druid')
                        .replace('p', 'Paladin')
                        .replace('+', ' and ')
                }

                if (property.property == 'Attrib') {
                    return property.value.replace('sword', 'Sword')
                        .replace('axe', 'Axe')
                        .replace('club', 'Club')
                        .replace('distance', 'Distance')
                        .replace('magic', 'ML')
                        .replace('faster', 'Faster')
                        .replace('regen', 'Regeneration')
                }

                return property.value
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