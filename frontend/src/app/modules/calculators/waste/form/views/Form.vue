<template>
    <page-load>
        <page-title>
            <img :src="image_path('item', 3147)" class="margin-right-10">
            Supplies
            <span>Waste</span>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <ul class="tabs">
                    <tab-link tab="potions" active>
                        <img :src="image_path('item', 3146)" class="margin-right-5">
                        Potions
                    </tab-link>

                    <tab-link tab="ammunition">
                        <img :src="image_path('item', 919)" class="margin-right-5">
                        Ammunition
                    </tab-link>

                    <tab-link tab="runes">
                        <img :src="image_path('item', 2024)" class="margin-right-5">
                        Runes
                    </tab-link>

                    <tab-link tab="amulets">
                        <img :src="image_path('item', 178)" class="margin-right-5">
                        Amulets
                    </tab-link>

                    <tab-link tab="rings">
                        <img :src="image_path('item', 104)" class="margin-right-5">
                        Rings
                    </tab-link>
                </ul>

                <form action="" @submit.prevent="onSubmit" ref="form">
                    <tab-content tab="potions" active>
                        <div class="margin-top-10"></div>
                        <div class="inline" v-for="potion, index in potions">
                            <form-group>
                                <el-select
                                        clearable
                                        filterable
                                        class="field"
                                        v-model="potion.id"
                                        default-first-option
                                        placeholder="Choose Potion"
                                        popper-class="suppliesSelect"
                                        no-match-text="Não encontrado"
                                        :name="`potions[${index}][id]`"
                                >
                                    <el-option v-for="item in options.potions" :label="item.title" :value="item.id" :key="item.id">
                                        <img :src="image_path('item', item.id)" class="margin-right-10">
                                        <span>{{ item.title }}</span>
                                    </el-option>
                                </el-select>

                            </form-group>

                            <form-group>
                                <form-input :name="`potions[${index}][quantity]`" v-model="potion.quantity"
                                            placeholder="Quantity"/>
                            </form-group>

                            <form-group>
                                <form-input :name="`potions[${index}][price]`" v-model="potion.price"
                                            placeholder="Custom Price (each)"/>
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link" @click.prevent="add('potions')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('potions', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </tab-content>

                    <tab-content tab="ammunition">
                        <div class="margin-top-10"></div>
                        <div class="inline" v-for="ammunition, index in ammunitions">
                            <form-group>
                                <el-select
                                        clearable
                                        filterable
                                        class="field"
                                        v-model="ammunition.id"
                                        default-first-option
                                        placeholder="Choose Ammunition"
                                        popper-class="suppliesSelect"
                                        no-match-text="Não encontrado"
                                        :name="`ammunitions[${index}][id]`"
                                >
                                    <el-option v-for="item in options.ammunitions" :label="item.title" :value="item.id" :key="item.id">
                                        <img :src="image_path('item', item.id)" class="margin-right-10">
                                        <span>{{ item.title }}</span>
                                    </el-option>
                                </el-select>
                            </form-group>

                            <form-group>
                                <form-input :name="`ammunitions[${index}][quantity]`" v-model="ammunition.quantity"
                                            placeholder="Quantity"/>
                            </form-group>

                            <form-group>
                                <form-input :name="`ammunitions[${index}][price]`" v-model="ammunition.price"
                                            placeholder="Custom Price (each)"/>
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link" @click.prevent="add('ammunitions')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('potions', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </tab-content>

                    <tab-content tab="runes">
                        <div class="margin-top-10"></div>
                        <div class="inline" v-for="rune, index in runes">
                            <form-group>
                                <el-select
                                        clearable
                                        filterable
                                        class="field"
                                        v-model="rune.id"
                                        default-first-option
                                        placeholder="Choose Rune"
                                        popper-class="suppliesSelect"
                                        no-match-text="Não encontrado"
                                        :name="`runes[${index}][id]`"
                                >
                                    <el-option v-for="item in options.runes" :label="item.title" :value="item.id" :key="item.id">
                                        <img :src="image_path('item', item.id)" class="margin-right-10">
                                        <span>{{ item.title }}</span>
                                    </el-option>
                                </el-select>
                            </form-group>

                            <form-group>
                                <form-input :name="`runes[${index}][quantity]`" v-model="rune.quantity"
                                            placeholder="Quantity"/>
                            </form-group>

                            <form-group>
                                <form-input :name="`runes[${index}][price]`" v-model="rune.price"
                                            placeholder="Custom Price (each)"/>
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link" @click.prevent="add('runes')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('runes', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </tab-content>

                    <tab-content tab="amulets">
                        <div class="margin-top-10"></div>
                        <div class="inline" v-for="amulet, index in amulets">
                            <form-group>
                                <el-select
                                        clearable
                                        filterable
                                        class="field"
                                        v-model="amulet.id"
                                        default-first-option
                                        placeholder="Choose Amulet"
                                        popper-class="suppliesSelect"
                                        no-match-text="Não encontrado"
                                        :name="`amulets[${index}][id]`"
                                >
                                    <el-option v-for="item in options.amulets" :label="item.title" :value="item.id" :key="item.id">
                                        <img :src="image_path('item', item.id)" class="margin-right-10">
                                        <span>{{ item.title }}</span>
                                    </el-option>
                                </el-select>
                            </form-group>

                            <form-group>
                                <form-input :name="`amulets[${index}][quantity]`" v-model="amulet.quantity"
                                            placeholder="Quantity"/>
                            </form-group>

                            <form-group>
                                <form-input :name="`amulets[${index}][price]`" v-model="amulet.price"
                                            placeholder="Custom Price (each)"/>
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link" @click.prevent="add('amulets')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('amulets', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </tab-content>

                    <tab-content tab="rings">
                        <div class="margin-top-10"></div>
                        <div class="inline" v-for="ring, index in rings">
                            <form-group>
                                <el-select
                                        clearable
                                        filterable
                                        class="field"
                                        v-model="ring.id"
                                        default-first-option
                                        placeholder="Choose Ring"
                                        popper-class="suppliesSelect"
                                        no-match-text="Não encontrado"
                                        :name="`rings[${index}][id]`"
                                >
                                    <el-option v-for="item in options.rings" :label="item.title" :value="item.id" :key="item.id">
                                        <img :src="image_path('item', item.id)" class="margin-right-10">
                                        <span>{{ item.title }}</span>
                                    </el-option>
                                </el-select>
                            </form-group>

                            <form-group>
                                <form-input :name="`rings[${index}][quantity]`" v-model="ring.quantity"
                                            placeholder="Quantity"/>
                            </form-group>

                            <form-group>
                                <form-input :name="`rings[${index}][price]`" v-model="ring.price"
                                            placeholder="Custom Price (each)"/>
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link" @click.prevent="add('rings')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('rings', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </tab-content>

                    <div class="tab-footer">
                        <button class="btn btn-success btn-lg btn-block" @click.prevent="onSubmit" :disabled="calculating">
                            <span v-if="calculating">
                                <i class="mdi mdi-loading"></i>
                                Calculating
                            </span>
                            <span v-else>Calculate</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'

    export default {
        data () {
            return {
                selected: '',
                potions: [{ id: null, quantity: null, price: null }],
                ammunitions: [{ id: null, quantity: null, price: null }],
                runes: [{ id: null, quantity: null, price: null }],
                amulets: [{ id: null, quantity: null, price: null }],
                rings: [{ id: null, quantity: null, price: null }],
                calculating: false
            }
        },

        computed: {
            options () {
                const potions     = this.$store.getters['waste/GET_POTIONS']
                const ammunitions = this.$store.getters['waste/GET_AMMUNITIONS']
                const runes       = this.$store.getters['waste/GET_RUNES']
                const amulets     = this.$store.getters['waste/GET_AMULETS']
                const rings       = this.$store.getters['waste/GET_RINGS']

                return {
                    potions,
                    ammunitions,
                    runes,
                    amulets,
                    rings
                }
            }
        },

        methods: {
            add (type) {
                this[type].push({ id: null, quantity: null, price: null })
            },

            remove (type, index) {
                if (this[type].length > 1) {
                    this[type].splice(index, 1)
                }
            },

            onSubmit () {
                const data = {
                    potions: this.potions,
                    ammunitions: this.ammunitions,
                    runes: this.runes,
                    amulets: this.amulets,
                    rings: this.rings,
                }

                this.calculating = true
                services.calculate(data)
                        .then(response => {
                            this.$router.push({ name: 'calculators.waste.result', params: { id: response.data.id } })
                            this.calculating = false
                        })
                        .catch(error => {
                            this.calculating = false
                        })
            }
        },

        mounted () {
            this.$store.dispatch('waste/FETCH_SUPPLIES')
        }
    }
</script>