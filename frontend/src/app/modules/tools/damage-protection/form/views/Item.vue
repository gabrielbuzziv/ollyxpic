<template>
    <el-popover
            placement="top-start"
            popper-class="equipment-details"
            :title="item.title">

        <div class="properties">
            <span class="property" v-for="property in item.properties">
                <b>{{ getPropertyName(property) }}:</b>
                <span>{{ getPropertyValue(property) }}</span>
            </span>

            <span class="property">
                <b>Capacity:</b>
                <span>{{ item.capacity }} oz.</span>
            </span>
        </div>

        <button class="btn btn-primary btn-sm btn-block margin-top-20" @click.prevent="select">
            Equip
        </button>

        <div class="item" :class="{ 'active': active }" slot="reference">
            <img :src="image_path('item', item.id)">
        </div>
    </el-popover>
</template>

<script>
    export default {
        props: ['item', 'active'],

        methods: {
            select () {
                this.$emit('selected')
            },

            propertyInUse (property) {
                return this.item.properties.map(property => property.property).indexOf(property)
            },

            getPropertyName (property) {
                const properties = {
                    atk: 'Atk',
                    def: 'Def',
                    arm: 'Arm',
                    hit: 'Hit Chance',
                    vol: 'Volume',
                    death: 'Death Protection',
                    earth: 'Earth Protection',
                    energy: 'Energy Protection',
                    fire: 'Fire Protection',
                    ice: 'Ice Protection',
                    speed: 'Speed',
                    level: 'Level Required',
                    shielding: 'Shielding',
                    range: 'Range',
                    imbuement: 'Imbuement Slots',
                    'magic level': 'Magic Level',
                    'mana drain': 'Mana Drain Protection',
                    'life drain': 'Life Drain Protection',
                    'axe fighting': 'Axe Fighting',
                    'club fighting': 'Club Fighting',
                    'sword fighting': 'Sword Fighting',
                    'distance fighting': 'Distance Fighting',
                    'protection physical': 'Physical Protection'
                }

                return properties[property.property]
            },

            getPropertyValue (property) {
                switch (property.property) {
                    case 'range':
                        return `${property.value} sqm`
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
                        return `+${property.value}`
                    default:
                        return property.value
                }
            }
        }
    }
</script>