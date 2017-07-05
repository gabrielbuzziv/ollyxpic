<template>
    <el-dialog :visible.sync="visible" id="waste-modal">
        <ul class="menu">
            <li :class="{ 'active': active.potions }">
                <a href="#" @click.prevent="show('potions')">
                    Potions
                </a>
            </li>
            <li :class="{ 'active': active.ammunitions }">
                <a href="#" @click.prevent="show('ammunitions')">
                    Ammunitions
                </a>
            </li>
            <li :class="{ 'active': active.runes }">
                <a href="#" @click.prevent="show('runes')">
                    Runes
                </a>
            </li>
            <li :class="{ 'active': active.amulets }">
                <a href="#" @click.prevent="show('amulets')">
                    Amulets
                </a>
            </li>
            <li :class="{ 'active': active.rings }">
                <a href="#" @click.prevent="show('rings')">
                    Rings
                </a>
            </li>
        </ul>

        <div class="supplies" v-show="active.potions">
            <div class="supply" v-for="potion, index in supplies.potions">
                <div class="fields">
                    <el-select
                            clearable
                            filterable
                            class="field"
                            v-model="potion.data"
                            default-first-option
                            placeholder="Choose Potion"
                            popper-class="suppliesSelect"
                            no-match-text="Não encontrado"
                    >
                        <el-option v-for="item in options.potions" :label="item.title" :value="item" :key="item.id">
                            <img :src="image_path('item', item.id)" class="margin-right-10">
                            <span>{{ item.title }}</span>
                        </el-option>
                    </el-select>
                    <form-input class="field" v-model="potion.quantity" placeholder="Amount"/>
                    <div class="field">
                        <div class="input-group">
                            <form-input  v-model="potion.price" :placeholder="getPricePlaceholder(potion)"/>
                            <div class="input-group-addon">gp</div>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button @click.prevent="add('potions')">
                        <i class="mdi mdi-plus"></i>
                    </button>
                    <button @click.prevent="remove('potions', index)" v-if="index > 0">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="supplies" v-show="active.ammunitions">
            <div class="supply" v-for="ammunition, index in supplies.ammunitions">
                <div class="fields">
                    <el-select
                            clearable
                            filterable
                            class="field"
                            v-model="ammunition.data"
                            default-first-option
                            placeholder="Choose Ammunition"
                            popper-class="suppliesSelect"
                            no-match-text="Não encontrado"
                    >
                        <el-option v-for="item in options.ammunitions" :label="item.title" :value="item"
                                   :key="item.id">
                            <img :src="image_path('item', item.id)" class="margin-right-10">
                            <span>{{ item.title }}</span>
                        </el-option>
                    </el-select>
                    <form-input class="field" v-model="ammunition.quantity" placeholder="Amount"/>
                    <div class="field">
                        <div class="input-group">
                            <form-input  v-model="ammunition.price" :placeholder="getPricePlaceholder(ammunition)"/>
                            <div class="input-group-addon">gp</div>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button @click.prevent="add('ammunitions')">
                        <i class="mdi mdi-plus"></i>
                    </button>
                    <button @click.prevent="remove('ammunitions', index)" v-if="index > 0">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="supplies" v-show="active.runes">
            <div class="supply" v-for="rune, index in supplies.runes">
                <div class="fields">
                    <el-select
                            clearable
                            filterable
                            class="field"
                            v-model="rune.data"
                            default-first-option
                            placeholder="Choose Rune"
                            popper-class="suppliesSelect"
                            no-match-text="Não encontrado"
                    >
                        <el-option v-for="item in options.runes" :label="item.title" :value="item"
                                   :key="item.id">
                            <img :src="image_path('item', item.id)" class="margin-right-10">
                            <span>{{ item.title }}</span>
                        </el-option>
                    </el-select>
                    <form-input class="field" v-model="rune.quantity" placeholder="Amount"/>
                    <div class="field">
                        <div class="input-group">
                            <form-input  v-model="rune.price" :placeholder="getPricePlaceholder(rune)"/>
                            <div class="input-group-addon">gp</div>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button @click.prevent="add('runes')">
                        <i class="mdi mdi-plus"></i>
                    </button>
                    <button @click.prevent="remove('runes', index)" v-if="index > 0">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="supplies" v-show="active.amulets">
            <div class="supply" v-for="amulet, index in supplies.amulets">
                <div class="fields">
                    <el-select
                            clearable
                            filterable
                            class="field"
                            v-model="amulet.data"
                            default-first-option
                            placeholder="Choose Amulet"
                            popper-class="suppliesSelect"
                            no-match-text="Não encontrado"
                    >
                        <el-option v-for="item in options.amulets" :label="item.title" :value="item"
                                   :key="item.id">
                            <img :src="image_path('item', item.id)" class="margin-right-10">
                            <span>{{ item.title }}</span>
                        </el-option>
                    </el-select>
                    <form-input class="field" v-model="amulet.quantity" placeholder="Amount"/>
                    <div class="field">
                        <div class="input-group">
                            <form-input v-model="amulet.price" :placeholder="getPricePlaceholder(amulet)"/>
                            <div class="input-group-addon">gp</div>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button @click.prevent="add('amulets')">
                        <i class="mdi mdi-plus"></i>
                    </button>
                    <button @click.prevent="remove('amulets', index)" v-if="index > 0">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="supplies" v-show="active.rings">
            <div class="supply" v-for="ring, index in supplies.rings">
                <div class="fields">
                    <el-select
                            clearable
                            filterable
                            class="field"
                            v-model="ring.data"
                            default-first-option
                            placeholder="Choose Ring"
                            popper-class="suppliesSelect"
                            no-match-text="Não encontrado"
                    >
                        <el-option v-for="item in options.rings" :label="item.title" :value="item"
                                   :key="item.id">
                            <img :src="image_path('item', item.id)" class="margin-right-10">
                            <span>{{ item.title }}</span>
                        </el-option>
                    </el-select>
                    <form-input class="field" v-model="ring.quantity" placeholder="Amount"/>
                    <div class="field">
                        <div class="input-group">
                            <form-input v-model="ring.price" :placeholder="getPricePlaceholder(ring)"/>
                            <div class="input-group-addon">gp</div>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button @click.prevent="add('rings')">
                        <i class="mdi mdi-plus"></i>
                    </button>
                    <button @click.prevent="remove('rings', index)" v-if="index > 0">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="buttons margin-top-20">
            <button class="btn" @click.prevent="close">
                Cancel
            </button>

            <button class="btn btn-success" @click.prevent="calculate">
                Calculate
            </button>
        </div>

    </el-dialog>
</template>

<script type="text/babel">
    import { isEmpty } from 'lodash'

    export default {
        props: ['visible'],

        data () {
            return {
                temmate: null,

                active: {
                    potions: true,
                    ammunitions: false,
                    runes: false,
                    amulets: false,
                    runes: false,
                },

                supplies: {
                    potions: [{ data: null, quantity: null, price: null }],
                    ammunitions: [{ data: null, quantity: null, price: null }],
                    runes: [{ data: null, quantity: null, price: null }],
                    amulets: [{ data: null, quantity: null, price: null }],
                    rings: [{ data: null, quantity: null, price: null }]
                }
            }
        },

        computed: {
            options () {
                const potions     = this.$store.getters['waste/GET_POTIONS']
                const ammunitions = this.$store.getters['waste/GET_AMMUNITIONS']
                const runes       = this.$store.getters['waste/GET_RUNES']
                const amulets     = this.$store.getters['waste/GET_AMULETS']
                const rings       = this.$store.getters['waste/GET_RINGS']

                return { potions, ammunitions, runes, amulets, rings }
            }
        },

        methods: {
            load (teammate) {
                this.teammate = teammate
            },

            getPricePlaceholder (potion) {
                if (potion.data && potion.data.vendor_value) {
                    return potion.data.vendor_value
                }

                return `Price in gp`
            },

            show (menu) {
                this.active.potions     = false
                this.active.ammunitions = false
                this.active.runes       = false
                this.active.amulets     = false
                this.active.rings       = false
                this.active[menu]       = true
            },

            add (type) {
                this.supplies[type].push({ data: null, quantity: null, price: null })
            },

            remove (type, index) {
                if (index > 0) {
                    this.supplies[type].splice(index, 1)
                }
            },

            calculate () {
                const types = ['potions', 'ammunitions', 'runes', 'amulets', 'rings']

                const total = types.reduce((carry, type) => {
                    return carry + this.supplies[type].reduce((carry, supply) => {
                        if (! isEmpty(supply.quantity)) {
                            const price = isEmpty(supply.price) ? supply.data.vendor_value : supply.price
                            return carry + (supply.quantity * price)
                        }

                        return carry
                    }, 0)
                }, 0)

                this.teammate.supplies = this.supplies
                this.teammate.waste = total.toString()

                this.$root.$emit('waste::teammate', this.teammate)
                this.close()

            },

            close () {
                this.$emit('update:visible', false)
            }
        },

        mounted () {
            this.$store.dispatch('waste/FETCH_SUPPLIES')

            this.$root.$on('teammate::supply', teammate => this.load(teammate))
        },

        beforeDestroy () {
            this.$root.$off('teammate::supply')
        }
    }
</script>
