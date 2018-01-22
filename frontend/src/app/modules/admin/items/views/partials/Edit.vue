<template>
    <tr class="edit" v-if="show">
        <td colspan="4">
            <h4>Editing {{ item.title }}</h4>

            <form action="" class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-6 text-right">
                        Is a two handed weapon?
                    </label>

                    <div class="col-md-6">
                        <el-switch
                                v-model="twoHanded"
                                on-text=""
                                off-text=""
                                @change="updateTwoHanded">
                        </el-switch>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-6 text-right">
                        Imbuement Slots
                    </label>

                    <div class="col-md-6">
                        <el-select
                                v-model="imbuementsSlots"
                                @change="updateImbuements">
                            <el-option v-for="imbuement in imbuements"
                                       :value="imbuement.value"
                                       :label="imbuement.label"
                                       :key="imbuement.value" />
                        </el-select>
                    </div>
                </div>
            </form>
        </td>
    </tr>
</template>

<script>
    import services from '../../services'

    export default {
        props: ['item', 'show'],

        data () {
            return {
                twoHanded: false,
                imbuementsSlots: 0,
                imbuements: [
                    { label: 'None', value: 0 },
                    { label: '1 slot', value: 1 },
                    { label: '2 slots', value: 2 },
                    { label: '3 slots', value: 3 },
                ]
            }
        },

        methods: {
            updateProperty (property, value) {
                services.updateProperty(this.item.id, property, value)
            },

            updateTwoHanded (value) {
                return this.updateProperty('two-handed', value)
            },

            updateImbuements (value) {
                return this.updateProperty('imbuements', value)
            }
        }
    }
</script>