<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path('item', 208)" alt="">
            Damage
            <span>Protection</span>
        </page-title>

        <div class="col-md-2">
            <panel class="panel-tibia">
                <div class="equips">
                    <div :class="['equip', category.slot, dragging[category.slot] ? 'dragging' : '', equipments[category.slot].length ? 'active' : '']"
                         v-for="category in categories" :key="category.id">
                        <draggable class="draggable" v-model="equipments[category.slot]"
                                   :options="{ group: category.slot }"
                                   @change="onChange">
                            <div class="item" v-if="equipments[category.slot].length">
                                <el-popover placement="top"
                                            popper-class="equipmentPopper">
                                    <strong>{{ equipments[category.slot][0].item.title }}</strong>

                                    <ul class="props">
                                        <li v-for="prop in equipments[category.slot][0].item.props">
                                            <b>{{ prop.description }}:</b> {{ getPropValue(prop) }}
                                        </li>
                                    </ul>

                                    <img :src="image_path('item', equipments[category.slot][0].item.id)"
                                         slot="reference">
                                </el-popover>
                            </div>
                        </draggable>
                    </div>
                </div>
            </panel>
        </div>

        <div class="col-md-10">
            <ul class="tabs">
                <tab-link :tab="category.id" :active="isShowing(category.id)" v-for="category in categories"
                          @click="show(category.id)" :key="category.id">
                    <el-tooltip :content="category.name" placement="top">
                        <img :src="image_path('item', category.image)" class="margin-right-5">
                    </el-tooltip>
                </tab-link>
            </ul>

            <tab-content class="items" :tab="category.id" :active="isShowing(category.id)"
                         v-for="category in categories" :key="category.id">
                <div v-if="! loading">
                    <draggable v-model="category.items" :options="{ group: category.slot }"
                               @start="startDrag(category.slot)" @end="endDrag(category.slot)">
                        <div class="item" v-for="item in category.items">
                            <el-popover popper-class="equipmentPopper">
                                <strong>{{ item.item.title }}</strong>

                                <ul class="props">
                                    <li v-for="prop in item.item.props">
                                        <b>{{ prop.description }}:</b> {{ getPropValue(prop) }}
                                    </li>
                                </ul>

                                <img :src="image_path('item', item.item.id)" slot="reference">
                            </el-popover>
                        </div>
                    </draggable>
                </div>

                <div v-else>
                    <div class="text-center">
                        <img src="/src/assets/images/loader.gif" alt="">
                    </div>
                </div>
            </tab-content>
        </div>

        <div class="col-md-12 margin-top-30">
            <panel>
                <div class="alert alert-warning">
                    <p>Note: The percentage is not added but multiplied.</p>
                </div>

                <table class="info-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

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
                            <td class="text-center" colspan="2">{{ weight }} oz.</td>
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
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { isEmpty } from 'lodash'

    export default {
        data () {
            return {
                dragging: {
                    amulet: false,
                    helmet: false,
                    backpack: false,
                    armor: false,
                    shield: false,
                    ring: false,
                    legs: false,
                    ammunition: false,
                    boots: false,
                    weapon: false
                },

                showing: '',
                categories: [
                    { slot: 'weapon', id: 'axe', name: 'Axe', image: 1350, open: false, items: [], },
                    { slot: 'weapon', id: 'sword', name: 'Sword', image: 1431, open: false, items: [], },
                    { slot: 'weapon', id: 'club', name: 'Club', image: 98, open: false, items: [], },
                    { slot: 'weapon', id: 'distance', name: 'Distance', image: 2319, open: false, items: [], },
                    { slot: 'weapon', id: 'wand', name: 'Wands & Rods', image: 2352, open: false, items: [], },
                    { slot: 'shield', id: 'shield', name: 'Shields', image: 2195, open: false, items: [], },
                    { slot: 'helmet', id: 'helmet', name: 'Helmets', image: 522, open: false, items: [], },
                    { slot: 'armor', id: 'armor', name: 'Armors', image: 2199, open: false, items: [], },
                    { slot: 'legs', id: 'legs', name: 'Legs', image: 2198, open: false, items: [], },
                    { slot: 'boots', id: 'boots', name: 'Boots', image: 1, open: false, items: [], },
                    { slot: 'backpack', id: 'backpack', name: 'Backpacks', image: 2943, open: false, items: [], },
                    { slot: 'amulet', id: 'amulet', name: 'Amulets', image: 1042, open: false, items: [], },
                    { slot: 'ring', id: 'ring', name: 'Rings', image: 2348, open: false, items: [], },
                    { slot: 'ammunition', id: 'ammunition', name: 'Ammunitions', image: 811, open: false, items: [] },
                ],

                equipments: {
                    amulet: [],
                    helmet: [],
                    backpack: [],
                    armor: [],
                    shield: [],
                    ring: [],
                    legs: [],
                    ammunition: [],
                    boots: [],
                    weapon: []
                },

                list: {
                    amulet: [],
                    helmet: [],
                    backpack: [],
                    armor: [],
                    shield: [],
                    ring: [],
                    legs: [],
                    ammunitions: [],
                    boots: [],
                    axe: [],
                    club: [],
                    sword: [],
                    distance: [],
                    wand: [],
                },

                loading: false
            }
        },

        computed: {
            _ () {
                return _
            },

            slots () {
                return this.categories.map(category => category.slot).filter((slot, index, self) => self.indexOf(slot) === index)
            },

            properties () {
                return this.slots.map(slot => {
                    if (this.equipments[slot].length) {
                        return this.equipments[slot][0].item.props
                    }

                    return []
                }).filter(property => ! isEmpty(property))
            },

            weight () {
                return this.slots.map(slot => {
                    if (this.equipments[slot].length) {
                        return parseInt(this.equipments[slot][0].item.capacity)
                    }

                    return 0
                }).reduce((carry, weight) => {
                    return carry + weight
                }, 0)
            },

            level () {
                const levels = this.getProperties('level')

                return levels.reduce((carry, level) => {
                    if (level.value > carry) {
                        return level.value
                    }

                    return carry
                }, 0)
            },
        },

        methods: {
            isShowing (type) {
                if (this.showing == type) {
                    return true
                }

                return false
            },

            onChange (event) {
                if (event.added) {
                    const item = event.added.element
                    const categoryIndex = this.categories.map(category => category.id).indexOf(item.category)
                    let category = this.categories[categoryIndex]

                    this.equipments[category.slot] = [item]

                    this.resetCategories(this.categories[categoryIndex].slot, item.category)
                }
            },

            resetCategories (slot, exception) {
                const categories = this.categories.filter(category => category.slot == slot)

                categories.forEach(category => {
                    const index = this.categories.map(category => category.id).indexOf(category.id)

                    if (category.id != exception) {
                        this.categories[index].items = this.list[category.id]
                    } else {
                        const index = this.categories.map(category => category.id).indexOf(exception)
                        const item = this.equipments[category.slot][0]
                        this.categories[index].items = this.list[category.id].filter(list => list.item_id != item.item_id)
                    }
                })
            },

            startDrag (type) {
                this.dragging[type] = true
            },

            endDrag (type) {
                this.dragging[type] = false
            },

            show (type) {
                const categoryIndex = this.categories.map(category => category.id).indexOf(type)
                if (this.categories[categoryIndex].items.length == 0) {
                    this.loading = true
                    services.searchItem(type)
                        .then(response => {
                            this.list[type] = response.data
                            this.categories[categoryIndex].items = response.data
                            this.showing = type
                            this.loading = false
                        })
                        .catch(() => this.loading = false)
                } else {
                    this.showing = type
                }
            },

            getProperties (type) {
                if (this.properties.length) {
                    return this.properties.reduce((carry, property) => {
                        if (property.filter(prop => prop.description == type).length) {
                            carry.push(property.filter(prop => prop.description == type)[0])
                        }
                        return carry
                    }, [])
                }

                return []
            },

            getAttribute (type) {
                if (this.properties.length) {
                    const properties = this.getProperties(type)

                    return properties.reduce((carry, property) => {
                        return carry + property.value
                    }, 0)
                }

                return 0
            },

            getResistance (damage) {
                if (this.properties.length) {
                    const properties = this.getProperties(damage)

                    return (100 - properties.reduce((carry, property) => {
                        return carry - (carry * property.value)
                    }, 100)).formatMoney(2, '.', '.')
                }

                return 0
            },

            getPropValue (prop) {
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

                if (percentage.indexOf(prop.description) > - 1) {
                    return `${prop.value} %`
                }

                if (percentageMultiple.indexOf(prop.description) > - 1) {
                    const value = prop.value * 100
                    return `${value} %`
                }

                return prop.value
            }
        },

        mounted () {
            this.show('armor')

            services.getCategories()
                .then(response => {
                    this.categories = response.data
                })
        }
    }
</script>
