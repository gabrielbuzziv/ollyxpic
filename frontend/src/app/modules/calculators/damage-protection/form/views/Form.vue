<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path('item', 1014)" alt="">
            Damage
            <span>Protection</span>
        </page-title>

        <div class="col-md-12">
            <panel>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Item</th>
                            <th>Arm</th>
                            <th>Category</th>
                            <th>Usable</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="item in editItems">
                            <td>
                                <img :src="image_path('item', item.id)" alt="">
                            </td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.arm }}</td>
                            <td>
                                <select v-model="item.category">
                                    <option value="amulet">Amulet</option>
                                    <option value="helmet">Helmet</option>
                                    <option value="backpack">Backpack</option>
                                    <option value="axe">Axe</option>
                                    <option value="sword">Sword</option>
                                    <option value="club">Club</option>
                                    <option value="distance">Distance</option>
                                    <option value="armor">Armor</option>
                                    <option value="shield">Shield</option>
                                    <option value="legs">Legs</option>
                                    <option value="rings">Ring</option>
                                    <option value="ammunition">Ammunition</option>
                                    <option value="boots">Boots</option>
                                </select>
                            </td>
                            <td>
                                <el-checkbox v-model="item.usable"></el-checkbox>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn-success" @click="saveIt">Save ititem_</button>
            </panel>
        </div>

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
                        <div class="amulet"></div>
                        <div class="helmet" :class="{ 'dragging': dragging.helmets }">
                            <draggable class="draggable" v-model="helmet" :options="{ group:'helmet' }"
                                       @change="onChangeHelmet">
                                <div class="item" v-if="helmet.length">
                                    <el-popover :title="helmet[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', helmet[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="backpack" :class="{ 'dragging': dragging.backpacks }">
                            <draggable class="draggable" v-model="backpack" :options="{ group:'backpack' }"
                                       @change="onChangeBackpack">
                                <div class="item" v-if="backpack.length">
                                    <el-popover :title="backpack[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', backpack[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="weapon" :class="{ 'dragging': dragging.weapons }">
                            <draggable class="draggable" v-model="weapon" :options="{ group:'weapon' }"
                                       @change="onChangeWeapon">
                                <div class="item" v-if="weapon.length">
                                    <el-popover :title="weapon[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', weapon[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="armor" :class="{ 'dragging': dragging.armors }">
                            <draggable class="draggable" v-model="armor" :options="{ group:'armor' }"
                                       @change="onChangeArmor">
                                <div class="item" v-if="armor.length">
                                    <el-popover :title="armor[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', armor[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="shield" :class="{ 'dragging': dragging.shields }">
                            <draggable class="draggable" v-model="shield" :options="{ group:'shield' }"
                                       @change="onChangeShield">
                                <div class="item" v-if="shield.length">
                                    <el-popover :title="shield[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', shield[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="ring" :class="{ 'dragging': dragging.rings }">
                            <draggable class="draggable" v-model="ring" :options="{ group:'ring' }"
                                       @change="onChangeRing">
                                <div class="item" v-if="ring.length">
                                    <el-popover :title="ring[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', ring[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="legs" :class="{ 'dragging': dragging.legs }">
                            <draggable class="draggable" v-model="leg" :options="{ group:'leg' }"
                                       @change="onChangeLeg">
                                <div class="item" v-if="leg.length">
                                    <el-popover :title="leg[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', leg[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="ammunition" :class="{ 'dragging': dragging.ammunitions }">
                            <draggable class="draggable" v-model="ammunition" :options="{ group:'ammunition' }"
                                       @change="onChangeAmmunition">
                                <div class="item" v-if="ammunition.length">
                                    <el-popover :title="ammunition[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', ammunition[0].id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </div>
                        <div class="boots" :class="{ 'dragging': dragging.boots }">
                            <draggable class="draggable" v-model="boot" :options="{ group:'boot' }"
                                       @change="onChangeBoot">
                                <div class="item" v-if="boot.length">
                                    <el-popover :title="boot[0].title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', boot[0].id)" slot="reference">
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
                        <transition name="fadeIn">
                            <draggable v-model="helmets" :options="{ group: 'helmet' }" @start="startDrag('helmets')"
                                       @end="endDrag('helmets')" v-show="isShowing('helmets')">
                                <div class="item" v-for="helmet in helmets">
                                    <el-popover :title="helmet.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', helmet.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="armors" :options="{ group: 'armor' }" @start="startDrag('armors')"
                                       @end="endDrag('armors')" v-show="isShowing('armors')">
                                <div class="item" v-for="armor in armors">
                                    <el-popover :title="armor.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', armor.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="backpacks" :options="{ group: 'backpack' }"
                                       @start="startDrag('backpacks')"
                                       @end="endDrag('backpacks')" v-show="isShowing('backpacks')">
                                <div class="item" v-for="backpack in backpacks">
                                    <el-popover :title="backpack.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', backpack.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="weapons" :options="{ group: 'weapon' }" @start="startDrag('weapons')"
                                       @end="endDrag('weapons')" v-show="isShowing('weapons')">
                                <div class="item" v-for="weapon in weapons">
                                    <el-popover :title="weapon.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', weapon.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="shields" :options="{ group: 'shield' }" @start="startDrag('shields')"
                                       @end="endDrag('shields')" v-show="isShowing('shields')">
                                <div class="item" v-for="shield in shields">
                                    <el-popover :title="shield.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', shield.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="rings" :options="{ group: 'ring' }" @start="startDrag('rings')"
                                       @end="endDrag('rings')" v-show="isShowing('rings')">
                                <div class="item" v-for="ring in rings">
                                    <el-popover :title="ring.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', ring.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="legs" :options="{ group: 'leg' }" @start="startDrag('legs')"
                                       @end="endDrag('legs')" v-show="isShowing('legs')">
                                <div class="item" v-for="leg in legs">
                                    <el-popover :title="leg.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', leg.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="ammunitions" :options="{ group: 'ammunition' }"
                                       @start="startDrag('ammunitions')"
                                       @end="endDrag('ammunitions')" v-show="isShowing('ammunitions')">
                                <div class="item" v-for="ammunition in ammunitions">
                                    <el-popover :title="ammunition.title" placement="top"
                                                popper-class="equipmentPopper">
                                        <img :src="image_path('item', ammunition.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>

                        <transition name="fadeIn">
                            <draggable v-model="boots" :options="{ group: 'boot' }" @start="startDrag('boots')"
                                       @end="endDrag('boots')" v-show="isShowing('boots')">
                                <div class="item" v-for="boot in boots">
                                    <el-popover :title="boot.title" placement="top" popper-class="equipmentPopper">
                                        <img :src="image_path('item', boot.id)" slot="reference">
                                    </el-popover>
                                </div>
                            </draggable>
                        </transition>
                    </div>
                </div>
            </panel>
        </div>

        <div class="col-md-12">
            <panel title="Attributes">
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
                    { id: 'amulets', name: 'Amulets', image: 169, open: false },
                    { id: 'helmets', name: 'Helmets', image: 147, open: false },
                    { id: 'armors', name: 'Armors', image: 133, open: false },
                    { id: 'backpacks', name: 'Backpacks', image: 501, open: false },
                    { id: 'weapons', name: 'Weapons', image: 31, open: false },
                    { id: 'shields', name: 'Shields', image: 209, open: false },
                    { id: 'rings', name: 'Rings', image: 112, open: false },
                    { id: 'legs', name: 'legs', image: 154, open: false },
                    { id: 'ammunitions', name: 'Ammunitions', image: 217, open: false },
                    { id: 'boots', name: 'Boots', image: 1, open: false },
                ],

                amulets: [],
                amulet: [],

                helmets: [],
                helmet: [],

                armors: [],
                armor: [],

                backpacks: [],
                backpack: [],

                weapons: [],
                weapon: [],

                shields: [],
                shield: [],

                rings: [],
                ring: [],

                legs: [],
                leg: [],

                ammunitions: [],
                ammunition: [],

                boots: [],
                boot: [],

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

            onChangeAmulet (event) {
                this.onChange('amulet', event.added.element)
            },

            onChangeHelmet (event) {
                this.onChange('helmet', event.added.element)
            },

            onChangeBackpack (event) {
                this.onChange('backpack', event.added.element)
            },

            onChangeWeapon (event) {
                this.onChange('weapon', event.added.element)
            },

            onChangeArmor (event) {
                this.onChange('armor', event.added.element)
            },

            onChangeShield (event) {
                this.onChange('shield', event.added.element)
            },

            onChangeRing (event) {
                this.onChange('ring', event.added.element)
            },

            onChangeLeg (event) {
                this.onChange('leg', event.added.element)
            },

            onChangeAmmunition (event) {
                this.onChange('ammunition', event.added.element)
            },

            onChangeBoot (event) {
                this.onChange('boot', event.added.element)
            },



            onChange (type, item) {
                if (this[type].length > 1) {
                    const items = `${type}s`
                    this[items].push(this[type][1])

                    this[items] = this[items].sort((a, b) => {
                        return a.id - b.id
                    })
                }

                this[type] = [item]
            },

            startDrag (type) {
                this.dragging[type] = true
            },

            endDrag (type) {
                this.dragging[type] = false
            },

            show (type) {
                if (this[type].length == 0) {
                    services.searchItem(type)
                            .then(response => {
                                this[type]   = response.data
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

        mounted () {
            services.searchItem('Weapon')
                    .then(response => {
                        this.editItems = response.data.map(item => {
                            return {
                                id: item.id,
                                title: item.title,
                                category: 'sword',
                                usable: true,
                                arm: item.properties.filter(prop => prop.property == 'Atk')[0] ? item.properties.filter(prop => prop.property == 'Atk')[0].value : 0
                            }
                        }).sort((a, b) => {
                            return a.arm - b.arm
                        })
                    })
        }
    }
</script>
