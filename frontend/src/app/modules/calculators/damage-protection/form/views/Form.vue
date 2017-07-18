<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path('item', 1014)" alt="">
            Damage
            <span>Protection</span>
        </page-title>

        <div class="col-md-4">
            <panel>
                <div class="equips">
                    <div :class="['equip', category.slot, dragging[category.slot] ? 'dragging' : '']"
                         v-for="category in categories">
                        <draggable class="draggable" v-model="category.current"
                                   :options="{ group: category.slot }"
                                   @change="onChange">
                            <div class="item" v-if="category.current.length">
                                <el-popover :title="category.current[0].item.title" placement="top"
                                            popper-class="equipmentPopper">
                                    <img :src="image_path('item', category.current[0].item.id)"
                                         slot="reference">
                                </el-popover>
                            </div>
                        </draggable>
                    </div>
                </div>
            </panel>
        </div>

        <div class="col-md-8">
            <ul class="tabs">
                <tab-link :tab="category.id" :active="isShowing(category.id)" v-for="category in categories" @click="show(category.id)">
                    <el-tooltip :content="category.name" placement="top">
                        <img :src="image_path('item', category.image)" class="margin-right-5">
                    </el-tooltip>
                </tab-link>
            </ul>

            <tab-content class="items" :tab="category.id" :active="isShowing(category.id)" v-for="category in categories">
                <draggable v-model="category.items" :options="{ group: category.slot }"
                           @start="startDrag(category.slot)" @end="endDrag(category.slot)">
                    <div class="item" v-for="item in category.items">
                        <el-popover :title="item.item.title" popper-class="equipmentPopper">
                            <img :src="image_path('item', item.item.id)" slot="reference">
                        </el-popover>
                    </div>
                </draggable>
            </tab-content>
        </div>

        <div class="col-md-12">
            <div class="wrap">
                <div class="set">
                    <div :class="[category.slot, dragging[category.slot] ? 'dragging' : '']"
                         v-for="category in categories">
                        <draggable class="draggable" v-model="category.current"
                                   :options="{ group: category.slot }"
                                   @change="onChange">
                            <div class="item" v-if="category.current.length">
                                <el-popover :title="category.current[0].item.title" placement="top"
                                            popper-class="equipmentPopper">
                                    <img :src="image_path('item', category.current[0].item.id)"
                                         slot="reference">
                                </el-popover>
                            </div>
                        </draggable>
                    </div>
                    <div class="soul"></div>
                    <div class="capacity"></div>
                </div>

            </div>
        </div>

        <div class="col-md-12">
            <panel>
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
                            <td class="text-center title">Required Level</td>
                            <td class="text-center title">Weight</td>
                            <td class="text-center title" colspan="2">Regeneration</td>
                        </tr>

                        <tr>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center" colspan="2">0</td>
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
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                        </tr>

                        <tr rowspan="2">
                            <td class="text-center head" rowspan="2">Skilling</td>
                            <td class="text-center title">Magic Level</td>
                            <td class="text-center title">Fist Fighting</td>
                            <td class="text-center title">Sword Fighting</td>
                            <td class="text-center title">Axe Fighting</td>
                            <td class="text-center title">Club Fihghting</td>
                            <td class="text-center title" colspan="2">Distance Fighting</td>
                            <td class="text-center title" colspan="2">Shielding</td>
                        </tr>

                        <tr>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-center" colspan="2">0</td>
                            <td class="text-center" colspan="2">0</td>
                        </tr>
                    </tbody>
                </table>

                <!--<div class="alert alert-warning margin-bottom-0">-->
                <!--<p>Remember that the percentage reduction is based in</p>-->
                <!--</div>-->
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
                    weapon: false,
                    armor: false,
                    shield: false,
                    ring: false,
                    legs: false,
                    ammunition: false,
                    boots: false,
                },

                showing: '',
                categories: [
                    { slot: 'weapon', id: 'axe', name: 'Axe', image: 1350, open: false, items: [], current: [] },
                    { slot: 'weapon', id: 'sword', name: 'Sword', image: 1431, open: false, items: [], current: [] },
                    { slot: 'weapon', id: 'club', name: 'Club', image: 98, open: false, items: [], current: [] },
                    { slot: 'weapon', id: 'distance', name: 'Distance', image: 2319, open: false, items: [], current: [] },
                    { slot: 'weapon', id: 'wand', name: 'Wands & Rods', image: 2352, open: false, items: [], current: [] },
                    { slot: 'shield', id: 'shield', name: 'Shields', image: 2195, open: false, items: [], current: [] },
                    { slot: 'helmet', id: 'helmet', name: 'Helmets', image: 522, open: false, items: [], current: [] },
                    { slot: 'armor', id: 'armor', name: 'Armors', image: 2199, open: false, items: [], current: [] },
                    { slot: 'legs', id: 'legs', name: 'Legs', image: 2198, open: false, items: [], current: [] },
                    { slot: 'boots', id: 'boots', name: 'Boots', image: 1, open: false, items: [], current: [] },
                    { slot: 'backpack', id: 'backpack', name: 'Backpacks', image: 2943, open: false, items: [], current: [] },
                    { slot: 'amulet', id: 'amulet', name: 'Amulets', image: 1042, open: false, items: [], current: [] },
                    { slot: 'ring', id: 'ring', name: 'Rings', image: 2348, open: false, items: [], current: [] },
                    { slot: 'ammunition', id: 'ammunition', name: 'Ammunitions', image: 811, open: false, items: [], current: [] },
                ],

                list: {
                    amulet: [],
                    helmet: [],
                    backpack: [],
                    armor: [],
                    shield: [],
                    ring: [],
                    legs: [],
                    ammunitions: [],
                    boots: []
                },

                editItems: []
            }
        },

        computed: {
            _ () {
                return _
            }
        },

        methods: {
            isShowing (type) {
                if (this.showing == type) {
                    return true
                }

                return false
            },

            onChange (event) {
                const item          = event.added.element
                const categoryIndex = this.categories.map(category => category.id).indexOf(item.category)
                let category        = this.categories[categoryIndex]

                this.categories[categoryIndex].current = [item]
                this.categories[categoryIndex].items   = this.list[category.id].filter(list => list.item_id != item.item_id)
            },

            startDrag (type) {
                console.log(type)
                this.dragging[type] = true
            },

            endDrag (type) {
                console.log(type)
                this.dragging[type] = false
            },

            show (type) {
                const categoryIndex = this.categories.map(category => category.id).indexOf(type)
                if (this.categories[categoryIndex].items.length == 0) {
                    services.searchItem(type)
                            .then(response => {
                                this.list[type]                      = response.data
                                this.categories[categoryIndex].items = response.data
                                this.showing                         = type
                            })
                } else {
                    this.showing = type
                }
            },

            saveIt () {
                services.saveItem(this.editItems)
            }
        },

        mounted () {
            this.show('armor')
        }
    }
</script>
