<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path('item', 1014)" alt="">
            Damage
            <span>Protection</span>
        </page-title>

        <!--<div class="col-md-12">-->
            <!--<panel>-->
                <!--<table class="table">-->
                    <!--<thead>-->
                        <!--<tr>-->
                            <!--<th></th>-->
                            <!--<th>Item</th>-->
                            <!--<th>Arm</th>-->
                            <!--<th>Category</th>-->
                            <!--<th>Usable</th>-->
                        <!--</tr>-->
                    <!--</thead>-->

                    <!--<tbody>-->
                        <!--<tr v-for="item in editItems">-->
                            <!--<td>-->
                                <!--<img :src="image_path('item', item.id)" alt="">-->
                            <!--</td>-->
                            <!--<td>{{ item.title }}</td>-->
                            <!--<td>{{ item.arm }}</td>-->
                            <!--<td>-->
                                <!--<select v-model="item.category">-->
                                    <!--<option value="amulet">Amulet</option>-->
                                    <!--<option value="helmet">Helmet</option>-->
                                    <!--<option value="backpack">Backpack</option>-->
                                    <!--<option value="axe">Axe</option>-->
                                    <!--<option value="sword">Sword</option>-->
                                    <!--<option value="club">Club</option>-->
                                    <!--<option value="distance">Distance</option>-->
                                    <!--<option value="armor">Armor</option>-->
                                    <!--<option value="shield">Shield</option>-->
                                    <!--<option value="legs">Legs</option>-->
                                    <!--<option value="rings">Ring</option>-->
                                    <!--<option value="ammunition">Ammunition</option>-->
                                    <!--<option value="boots">Boots</option>-->
                                <!--</select>-->
                            <!--</td>-->
                            <!--<td>-->
                                <!--<el-checkbox v-model="item.usable"></el-checkbox>-->
                            <!--</td>-->
                        <!--</tr>-->
                    <!--</tbody>-->
                <!--</table>-->

                <!--<button class="btn btn-success" @click="saveIt">Save ititem_</button>-->
            <!--</panel>-->
        <!--</div>-->

        <div class="col-md-12">
            <panel>
                <div class="categories">
                    <button @click="show(category.id)" v-for="category in categories">
                        <img :src="image_path('item', category.image)">
                    </button>
                </div>
            </panel>
        </div>

        <div class="col-md-3">
            <panel>
                <div class="wrap">
                    <div class="set">
                        <div :class="category.id" v-for="category in categories">
                            <draggable class="draggable" v-model="category.current" :options="{ group: category.id }" @change="onTest">
                                <div class="item" v-if="category.current.length">
                                    <el-popover :title="category.current[0].item.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', category.current[0].item.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="soul"></div>
                        <div class="capacity"></div>
                    </div>
                </div>
            </panel>
        </div>

        <div class="col-md-9">
            <panel>
                <div class="wrap">
                    <div class="items">
                        <transition name="fadeIn" v-for="category in categories" :key="category.id">
                            <draggable v-model="category.items" :options="{ group: category.id }" v-if="isShowing(category.id)">
                                <div class="item" v-for="item in category.items">
                                    <el-popover :title="item.item.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', item.item.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>
                    </div>
                </div>
            </panel>
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
                    amulets: false,
                    helmets: false,
                    backpacks: false,
                    weapons: false,
                    armors: false,
                    shields: false,
                    rings: false,
                    legs: false,
                    ammunitions: false,
                    boots: false,
                },

                showing: '',
                categories: [
                    { id: 'amulet', name: 'Amulets', image: 169, open: false, items: [], current: [] },
                    { id: 'helmet', name: 'Helmets', image: 147, open: false, items: [], current: [] },
                    { id: 'armor', name: 'Armors', image: 133, open: false, items: [], current: [] },
                    { id: 'backpack', name: 'Backpacks', image: 501, open: false, items: [], current: [] },
                    { id: 'weapon', name: 'Weapons', image: 31, open: false, items: [], current: [] },
                    { id: 'shield', name: 'Shields', image: 209, open: false, items: [], current: [] },
                    { id: 'ring', name: 'Rings', image: 112, open: false, items: [], current: [] },
                    { id: 'legs', name: 'legs', image: 154, open: false, items: [], current: [] },
                    { id: 'ammunition', name: 'Ammunitions', image: 217, open: false, items: [], current: [] },
                    { id: 'boots', name: 'Boots', image: 1, open: false, items: [], current: [] },
                ],

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

            onTest (event) {
                const item = event.added.element
                const categoryIndex = this.categories.map(category => category.id).indexOf(item.category)
                let category = this.categories[categoryIndex]

                if (category.current.length > 1) {
//                    this.categories[categoryIndex].push(category.current[1])

//                    this.categories[categoryIndex].items = this.categories[categoryIndex].items.sort((a, b) => {
//                        return a.id - b.id
//                    })
                }

                this.categories[categoryIndex].current = [item]
            },


            onChange (type, item) {
                const categoryIndex = this.categories.map(category => category.id).indexOf(type)

                if (this.categories[categoryIndex].current.length > 1) {
                    this.categories[categoryIndex].push(this[type][1])

                    this.categories[categoryIndex].items = this.categories[categoryIndex].items.sort((a, b) => {
                        return a.id - b.id
                    })
                }

                this.categories[categoryIndex].current = [item]
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
                    services.searchItem(type)
                            .then(response => {
                                this.categories[categoryIndex].items = response.data
                                this.showing = type
                            })
                } else {
                    this.showing = type
                }
            },

            saveIt () {
                services.saveItem(this.editItems)
            }
        },

//        mounted () {
//            services.searchItem('Weapon')
//                    .then(response => {
//                        this.editItems = response.data.map(item => {
//                            return {
//                                id: item.id,
//                                title: item.title,
//                                category: 'distance',
//                                usable: false,
//                                arm: item.properties.filter(prop => prop.property == 'Atk')[0] ? item.properties.filter(prop => prop.property == 'Atk')[0].value : 0
//                            }
//                        }).sort((a, b) => {
//                            return a.arm - b.arm
//                        })
//
//                        this.showing = type
//                    })
//        }
    }
</script>
