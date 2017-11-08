<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path_by_name('item', 'great shield')" alt="">
            Damage
            <span>Protection</span>
        </page-title>

        <div class="row">
            <div class="col-md-2">
                <panel class="panel-tibia">
                    <div class="equips">
                        <div :class="['equip', index, slot.id ? 'active' : '']" v-for="slot, index in slots">
                            <div class="item" v-if="slot.id">
                                <img :src="image_path('item', slot.id)" @click.prenvet="onRemove(index)" title="Click to remove">
                            </div>
                        </div>
                    </div>
                </panel>
            </div>

            <div class="col-md-10">
                <ul class="tabs">
                    <tab-link :tab="category.name" v-for="category, index in categories" :key="category.id" :active="index == 0">
                        <img :src="image_path_by_name('item', category.image)" :title="category.title">
                    </tab-link>
                </ul>

                <tab-content class="items" :tab="category.name"
                             v-for="category, index in categories"
                             :key="category.id"
                             :active="index == 0"
                             @active="loadItems(category)">
                    <div class="item"
                         :class="{ 'active': isActiveItem(item, category.slot) }"
                         v-for="item in items"
                         :key="item.id">

                        <img :src="image_path('item', item.id)"
                             @click.prevent="onSelect(item, category.slot)">
                    </div>
                </tab-content>
            </div>
        </div>

        <div class="col-md-12 margin-top-30">
            <panel>
                <table class="info-table">
                    <tbody>
                        <tr rowspan="2">
                            <td class="text-center head" rowspan="2">Attributes</td>
                            <td class="text-center title">Arm</td>
                            <td class="text-center title">Def</td>
                            <td class="text-center title">Atk</td>
                            <td class="text-center title">Hit</td>
                            <td class="text-center title">Speed</td>
                            <td class="text-center title" colspan="2">Required Level</td>
                            <td class="text-center title" colspan="2">Weight</td>
                        </tr>

                        <tr>
                            <td class="text-center">{{ getAttribute('arm') }}</td>
                            <td class="text-center">{{ getAttribute('def') }}</td>
                            <td class="text-center">{{ getAttribute('atk') }}</td>
                            <td class="text-center">{{ getAttribute('hit') }} %</td>
                            <td class="text-center">{{ getAttribute('speed') }}</td>
                            <td class="text-center" colspan="2">{{ level }}</td>
                            <td class="text-center" colspan="2">{{ (weight).toFixed(2) }} oz.</td>
                        </tr>

                        <tr rowspan="2">
                            <td class="text-center head" rowspan="2">Resistances</td>
                            <td class="text-center title">Death</td>
                            <td class="text-center title">Energy</td>
                            <td class="text-center title">Earth</td>
                            <td class="text-center title">Fire</td>
                            <td class="text-center title">Ice</td>
                            <td class="text-center title">Holy</td>
                            <td class="text-center title">Physical</td>
                            <td class="text-center title">Life Drain</td>
                            <td class="text-center title">Mana Drain</td>
                        </tr>

                        <tr>
                            <td class="text-center">{{ getResistance('death') }} %</td>
                            <td class="text-center">{{ getResistance('energy') }} %</td>
                            <td class="text-center">{{ getResistance('earth') }} %</td>
                            <td class="text-center">{{ getResistance('fire') }} %</td>
                            <td class="text-center">{{ getResistance('ice') }} %</td>
                            <td class="text-center">{{ getResistance('holy') }} %</td>
                            <td class="text-center">{{ getResistance('protection physical') }} %</td>
                            <td class="text-center">{{ getResistance('life drain') }} %</td>
                            <td class="text-center">{{ getResistance('mana drain') }} %</td>
                        </tr>

                        <tr rowspan="2">
                            <td class="text-center head" rowspan="2">Skilling</td>
                            <td class="text-center title">Magic Level</td>
                            <td class="text-center title">Fist Fighting</td>
                            <td class="text-center title">Sword Fighting</td>
                            <td class="text-center title">Axe Fighting</td>
                            <td class="text-center title">Club Fighting</td>
                            <td class="text-center title" colspan="2">Distance Fighting</td>
                            <td class="text-center title" colspan="2">Shielding</td>
                        </tr>

                        <tr>
                            <td class="text-center">{{ getAttribute('magic level') }}</td>
                            <td class="text-center">{{ getAttribute('fist fighting') }}</td>
                            <td class="text-center">{{ getAttribute('sword fighting') }}</td>
                            <td class="text-center">{{ getAttribute('axe fighting') }}</td>
                            <td class="text-center">{{ getAttribute('club fighting') }}</td>
                            <td class="text-center" colspan="2">{{ getAttribute('distance fighting') }}</td>
                            <td class="text-center" colspan="2">{{ getAttribute('shielding') }}</td>
                        </tr>
                    </tbody>
                </table>
            </panel>
        </div>

        <div class="col-md-12 margin-top-30">
            <panel>

		<div class="col-md-4"></div>
		<div class="col-md-4"><form-input placeholder="incoming damage" v-model="incdmg" style="text-align:center;" /></div>
		<div class="col-md-4"></div>
                <table class="info-table"><br>
                    <tbody>
                        <tr rowspan="2">
                            <td class="text-center head" width="10%"></td>
                            <td class="text-center title" width="10%">Death</td>
                            <td class="text-center title" width="10%">Energy</td>
                            <td class="text-center title" width="10%">Earth</td>
                            <td class="text-center title" width="10%">Fire</td>
                            <td class="text-center title" width="10%">Ice</td>
                            <td class="text-center title" width="10%">Holy</td>
                            <td class="text-center title" width="10%">Physical</td>
                            <td class="text-center title" width="10%">Life Drain</td>
                            <td class="text-center title" width="10%">Mana Drain</td>
                        </tr>

                        <tr>
			    <td class="text-center head" rowspan="1">Reduced
                                    <el-tooltip content="Here is the amount the protection will remove.">
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
			    </td>
                            <td class="text-center">{{ (Showincdmg * getResistance('death') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('energy') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('earth') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('fire') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('ice') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('holy') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('protection physical') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('life drain') / 100).toFixed() }}</td>
                            <td class="text-center">{{ (Showincdmg * getResistance('mana drain') / 100).toFixed() }}</td>
                        </tr>


                        <tr>
			    <td class="text-center head">Total
                                    <el-tooltip content="Here is the incoming damage after resistance is calculated.">
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
			    </td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('death') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('energy') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('earth') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('fire') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('ice') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('holy') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('protection physical') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('life drain') / 100).toFixed() }}</td>
                            <td class="text-center">{{ ((Showincdmg) - Showincdmg * getResistance('mana drain') / 100).toFixed() }}</td>
                        </tr>

                    </tbody>
                </table>
            </panel>
        </div>


    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { isEmpty, forEach, reduce } from 'lodash'

    export default {
        data () {
            return {
                categories: [],
                items: [],
		incdmg: '0',
                slots: {
                    amulet: [],
                    helmet: [],
                    backpack: [],
                    weapon: [],
                    armor: [],
                    shield: [],
                    ring: [],
                    legs: [],
                    ammunition: [],
                    boots: [],
                }
            }
        },

        computed: {

	    Showincdmg () {
		return this.incdmg
	    },

            properties () {
                return reduce(this.slots, (carry, slot) => {
                    carry.push(slot.properties)
                    return carry
                }, [])
            },

            weight () {
                return reduce(this.slots, (carry, slot) => {
                    return typeof slot.capacity == 'number' ? carry + slot.capacity : carry
                }, 0)
            },

            level () {
                return this.getProperties('level').reduce((carry, level) => level.value > carry ? level.value : carry, 0)
            },
        },

        methods: {
            loadItems (category) {
                services.getItems(category.id)
                    .then(response => {
                        this.items = response.data
                    })
            },

            onSelect (item, slot) {
                this.slots[slot] = item
            },

            onRemove (slot) {
                this.slots[slot] = {}
            },

            isActiveItem (item, slot) {
                return item == this.slots[slot] ? true : false
            },

            getProperties (type) {
                if (this.properties.length) {
                    return this.properties.reduce((carry, properties) => {
                        if (typeof properties != 'undefined') {
                            if (properties.filter(property => property.property.toLowerCase() == type.toLowerCase()).length) {
                                carry.push(properties.filter(property => property.property.toLowerCase() == type.toLowerCase())[0])
                            }
                        }

                        return carry
                    }, [])
                }

                return []
            },

            getAttribute (type) {
                return this.properties.length ? this.getProperties(type).reduce((carry, property) => carry + parseInt(property.value), 0) : []
            },

            getResistance (damage) {
                return this.properties.length
                    ? (100 - this.getProperties(damage).reduce((carry, property) => carry - (carry * parseFloat(property.value)), 100)).formatMoney(2, '.', '.')
                    : 0
            },

            getPropValue (property) {
                const percentage = [
                    'hit'
                ]
                const percentageMultiple = [
                    'earth',
                    'fire',
                    'protection death',
                    'protection physical',
                    'ice',
                    'holy',
                    'energy',
                    'life drain'
                ]

                if (percentage.indexOf(property.property) > - 1) {
                    return `${property.value} %`
                }

                if (percentageMultiple.indexOf(property.property) > - 1) {
                    const value = property.value * 100
                    return `${value} %`
                }

                return property.value
            }
        },

        mounted () {
            services.getCategories()
                .then(response => {
                    this.categories = response.data

                    if (this.categories.length) {
                        this.loadItems(this.categories[0])
                    }
                })
        }
    }
</script>
