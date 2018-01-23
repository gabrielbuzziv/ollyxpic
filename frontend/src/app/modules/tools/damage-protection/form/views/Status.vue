<template>
    <div class="row margin-top-40" id="status">
        <div class="col-md-12">
            <panel class="status">
                <h4>Details</h4>

                <div class="properties-details">
                    <span class="property">
                        <span class="name">
                            Required Level
                        </span>
                        <span class="value">
                            {{ getPropertyValue('level') | property('level') }}
                        </span>
                    </span>

                    <span class="property">
                        <span class="name">
                            Weight
                        </span>
                        <span class="value">
                            {{ getPropertyValue('weight') | property('weight') }}
                        </span>
                    </span>

                    <span class="property">
                        <span class="name">
                            Speed
                        </span>
                        <span class="value">
                            {{ getPropertyValue('speed') | property('speed') }}
                        </span>
                    </span>
                </div>
            </panel>
        </div>

        <!-- Protection -->
        <div class="col-md-6">
            <panel class="status large">
                <h4>Protection</h4>

                <ul class="properties">
                    <li v-for="property in protection">
                        <span class="property">
                            {{ getPropertyLabel(property) }}
                        </span>
                        <span class="value"
                              :class="{ 'positive': getPropertyValue(property) > 0, 'negative': getPropertyValue(property) < 0 }">
                            {{ getPropertyValue(property) | property(property) }}
                        </span>
                    </li>
                </ul>
            </panel>
        </div>

        <!-- Damage -->
        <div class="col-md-6">
            <panel class="status large">
                <h4>Damage</h4>

                <ul class="properties">
                    <li v-for="property in damage">
                        <span class="property">
                            {{ getPropertyLabel(property) }}
                        </span>
                        <span class="value"
                              :class="{ 'positive': getPropertyValue(property) > 0, 'negative': getPropertyValue(property) < 0 }">
                            {{ getPropertyValue(property) | property(property) }}
                        </span>
                    </li>
                </ul>
            </panel>
        </div>
    </div>
</template>

<script>
    import Properties from './Properties'
    import { reduce, filter } from 'lodash'

    export default {
        props: ['slots'],

        data () {
            return {
                protection: [
                    'arm', 'def', 'death', 'energy', 'fire', 'ice', 'earth', 'protection physical', 'mana drain', 'life drain', 'shielding'
                ],
                damage: [
                    'atk', 'hit', 'range', 'magic level', 'axe fighting', 'club fighting', 'sword fighting', 'distance fighting'
                ]
            }
        },

        computed: {
            propertiesRaw () {
                const properties = this.slots
                    ? reduce(this.slots, (carry, item) => {
                        carry.push(item.properties)
                        return carry
                    }, [])
                        .filter(properties => properties != null)
                        .reduce((carry, properties) => carry.push(...properties) && carry, [])
                        .map(property => ({
                            id: property.id,
                            property: property.property,
                            value: parseFloat(property.value)
                        }))
                    : []

                return properties.reduce((carry, property) => {
                    const propertyIndex = carry.map(property => property.property).indexOf(property.property)
                    if (propertyIndex !== - 1) {
                        carry[propertyIndex].value = this.mergePropertyValue(property.property, carry[propertyIndex].value, property.value)
                        return carry
                    }
                    carry.push(property)
                    return carry
                }, [])
            },

            properties () {
                const properties = this.propertiesRaw.concat(this.imbuements)

                return properties.reduce((carry, property) => {
                    const propertyIndex = carry.map(property => property.property).indexOf(property.property)
                    if (propertyIndex !== - 1) {
                        carry[propertyIndex].value = this.mergePropertyValue(property.property, carry[propertyIndex].value, property.value)
                        return carry
                    }
                    carry.push(property)
                    return carry
                }, [])
            },

            imbuements () {
                const imbuements = this.slots
                    ? reduce(this.slots, (carry, item) => carry.push(item.imbuements) && carry, [])
                        .filter(imbuements => typeof imbuements == 'object' ? imbuements.length > 0 && imbuements != null : imbuements != null)
                        .reduce((carry, imbuements) => carry.push(...imbuements) && carry, [])
                        .map(imbuement => ({
                            property: imbuement.value.property,
                            value: parseFloat(imbuement.value.value)
                        }))
                    : []

                return imbuements.length
                    ? imbuements.reduce((carry, property) => {
                        const imbuementIndex = carry.map(property => property.property).indexOf(property.property)
                        if (imbuementIndex !== - 1) {
                            carry[imbuementIndex].value = this.mergePropertyValue(property.property, carry[imbuementIndex].value, property.value)
                            return carry
                        }
                        carry.push(property)
                        return carry
                    }, [])
                    : []
            }
        },

        filters: {
            property (value, property) {
                switch (property) {
                    case 'weight':
                        return `${value.toFixed(2)} oz.`
                    case 'range':
                        return `${value} sqm`
                    case 'vol':
                        return `${value} slots`
                    case 'hit':
                        return `${value}%`
                    case 'earth':
                    case 'energy':
                    case 'fire':
                    case 'ice':
                    case 'death':
                    case 'mana drain':
                    case 'life drain':
                    case 'protection physical':
                        return `${(value * 100).toFixed(2)}%`
                    case 'sword fighting':
                    case 'club fighting':
                    case 'axe fighting':
                    case 'distance fighting':
                    case 'shielding':
                    case 'magic level':
                    case 'speed':
                        return `+${value}`
                    default:
                        return value
                }
            }
        },

        methods: {
            mergePropertyValue (attribute, a, b) {
                switch (attribute) {
                    case 'def':
                    case 'level':
                        return a > b ? a : b
                    case 'earth':
                    case 'energy':
                    case 'fire':
                    case 'ice':
                    case 'death':
                    case 'mana drain':
                    case 'life drain':
                    case 'protection physical':
                        let aux = 100 - (100 * a)
                        aux = aux - (aux * b)
                        return (100 - aux) / 100
                    case 'arm':
                    default:
                        return a + b
                }
            },

            getPropertyLabel (property) {
                return Properties[property]
            },

            getPropertyValue (property) {
                const index = this.properties.map(property => property.property).indexOf(property)
                return index > - 1 ? this.properties[index].value : 0
            }
        }
    }
</script>