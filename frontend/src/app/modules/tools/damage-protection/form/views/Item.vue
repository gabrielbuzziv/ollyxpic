<template>
    <div class="col-md-2" v-if="item.visible">
        <div class="item" tabindex="1"  :class="{ 'active': item.active }" @click.prevent="equip">
            <el-tooltip popper-class="item-popper" placement="top">
                <template slot="content">
                    <div class="item-details">
                        <h4>{{ item.title }}</h4>

                        <ul class="properties">
                            <li v-for="property in item.properties">
                                <b>{{ getPropertyName(property) }}:</b>
                                {{ getPropertyValue(property) }}
                            </li>
                        </ul>
                    </div>
                </template>

                <div class="thumb">
                    <img :src="image_path('item', item.id)" />
                </div>
            </el-tooltip>

            <span class="name">{{ item.title }}</span>
        </div>
    </div>
</template>

<script>
    import Properties from './Properties'

    export default {
        props: ['item', 'category'],

        methods: {
            equip () {
                this.$emit('equiped', { item: this.item.id, category: this.category.id, slot: this.category.slot })
            },

            propertyInUse (property) {
                return this.item.properties.map(property => property.property).indexOf(property)
            },

            getPropertyName (property) {
                return Properties[property.property]
            },

            getPropertyValue (property) {
                switch (property.property) {
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
            }
        }
    }
</script>