<template>
    <div v-if="item.id">
        <el-popover
                v-model="popover"
                placement="top"
                triggle="click"
                popper-class="slot-item-popper">

            <h4>{{ item.title }}</h4>

            <ul class="properties">
                <li v-for="property in item.properties">
                    <b>{{ getPropertyName(property) }}:</b>
                    <span>{{ getPropertyValue(property) }}</span>
                </li>
            </ul>

            <button class="btn btn-danger btn-remove" @click.prevent="remove">
                <i class="mdi mdi-check margin-right-5"></i>
                Unequip
            </button>

            <div class="imbuements" v-if="imbuementsSlots">
                <h5>Imbuements</h5>

                <ul>
                    <li v-for="slot in imbuementsSlots">
                        <el-select v-model="imbuement[slot - 1]"
                                   :placeholder="`Slot ${slot}`"
                                   clearable="">
                            <el-option v-for="imbuement, index in imbuements"
                                       :label="imbuement.label"
                                       :value="imbuement"
                                       :key="index"/>
                        </el-select>
                    </li>
                </ul>

                <button class="btn btn-success btn-sm" @click.prevent="applyImbuement">
                    <i class="mdi mdi-check margin-right-5"></i>
                    Apply
                </button>
            </div>

            <img :src="image_path('item', item.id)" slot="reference"/>
        </el-popover>
    </div>
</template>

<script>
    import Properties from './Properties'
    import Imbuements from './Imbuements'

    export default {
        props: ['item', 'index'],

        data () {
            return {
                popover: false,
                imbuement: []
            }
        },

        computed: {
            imbuementsSlots () {
                const index = this.item.properties.map(property => property.property).indexOf('imbuements')
                return index !== - 1 ? parseInt(this.item.properties[index].value) : 0
            },

            imbuements () {
                switch (this.index) {
                    case 'helmet':
                        return Imbuements.helmet
                    case 'armor':
                        return Imbuements.armor
                    case 'shield':
                        return Imbuements.shield
                    case 'boots':
                        return []
                    case 'weapon':
                        return []
                }
            },
        },

        methods: {
            getPropertyName (property) {
                return Properties[property.property]
            },

            getPropertyValue (property) {
                switch (property.property) {
                    case 'two-handed':
                        return property.value ? 'Yes' : 'No'
                    case 'weight':
                        return `${property.value} oz.`
                    case 'range':
                        return `${property.value} sqm`
                    case 'vol':
                        return `${property.value} slots`
                    case 'hit':
                        return `${property.value}%`
                    case 'earth':
                    case 'energy':
                    case 'fire':
                    case 'ice':
                    case 'death':
                    case 'mana drain':
                    case 'life drain':
                    case 'protection physical':
                        return `${property.value * 100}%`
                    case 'sword fighting':
                    case 'club fighting':
                    case 'axe fighting':
                    case 'distance fighting':
                    case 'shielding':
                    case 'magic level':
                    case 'speed':
                        return `+${property.value}`
                    default:
                        return property.value
                }
            },

            applyImbuement () {
                this.item.imbuements = this.imbuement
                this.popover = false
                this.$emit('update:item', this.item)
            },

            remove () {
                this.$emit('remove', this.index)
                this.popover = false
            }
        },
    }
</script>