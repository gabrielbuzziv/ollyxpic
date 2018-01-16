<template>
    <page-load id="training">
        <page-title>
            <img :src="image_path_by_name('item', 'Spellbook of Ancient Arcana')" class="margin-right-5">
            <div class="title">
                <h2>Magic Level</h2>
                <span>Training</span>
            </div>
        </page-title>

        <div class="row vocations">
            <div class="col-md-3" v-for="vocation, index in vocations">
                <el-radio v-model="skills.vocation" :label="index">
                    {{ vocation.name }}
                </el-radio>
            </div>
        </div>

        <panel class="form">
            <div class="form-group col-md-4">
                <input type="text" class="form-control" placeholder="From" v-model="skills.from">
            </div>

            <div class="form-group col-md-4">
                <el-slider
                        v-model="skills.percentage"
                        :min="0"
                        :max="100"
                        :step="1"
                        :show-tooltip="false" />
            </div>

            <div class="form-group col-md-4">
                <input type="text" class="form-control" placeholder="To" v-model="skills.to">
            </div>
        </panel>

        <panel v-if="mana > 0">{{ mana }}</panel>

        <div class="row" v-if="mana > 0">
            <div class="col-md-3" v-for="potion in validPotions">
                <b>{{ potion.name }}</b><br>
                Amount: {{ calculatePotions(potion).amount.format() }} potions<br>
                Price: {{ calculatePotions(potion).price.format() }} gp<br>
                Time: {{ calculatePotions(potion).time }}
                <hr>
            </div>
        </div>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        data () {
            return {
                vocations: [
                    { name: 'Druid', active: true, multipler: 1.1, potions: ['mp', 'smp', 'gmp', 'ump'] },
                    { name: 'Sorcerer', active: false, multipler: 1.1, potions: ['mp', 'smp', 'gmp', 'ump'] },
                    { name: 'Knight', active: false, multipler: 3, potions: ['mp'] },
                    { name: 'Paladin', active: false, multipler: 1.4, potions: ['mp', 'smp', 'gsp', 'usp'] },
                ],

                potions: [
                    { id: 1, slug: 'mp', name: 'Mana Potion', mana: 100, price: 50 },
                    { id: 1, slug: 'smp', name: 'Strong Mana Potion', mana: 150, price: 80 },
                    { id: 1, slug: 'gmp', name: 'Great Mana Potion', mana: 200, price: 120 },
                    { id: 1, slug: 'ump', name: 'Ultimate Mana Potion', mana: 500, price: 350 },
                    { id: 1, slug: 'gsp', name: 'Great Spirit Potion', mana: 150, price: 190 },
                    { id: 1, slug: 'usp', name: 'Ultimate Spirit Potion', mana: 215, price: 350 },
                ],

                skills: {
                    vocation: 3,
                    from: null,
                    precentage: 0,
                    to: null,
                },
            }
        },

        computed: {
            vocation () {
                return this.vocations[this.skills.vocation]
            },

            validPotions () {
                return this.potions.filter(potion => this.vocation.potions.indexOf(potion.slug) > -1)
            },

            mana () {
                const from = parseInt(this.skills.from)
                const to = parseInt(this.skills.to)
                const percentage = (100 - parseInt(this.skills.percentage)) / 100
                const levels = Math.abs(from - to)
                let mana = this.calculateManaToML(from) * percentage

                if (levels > 1) {
                    for (let i = 1; i < levels; i ++) {
                        mana += this.calculateManaToML(from + i)
                    }
                }

                return typeof mana == 'number' ? mana.toFixed() : 0
            }
        },

        methods: {
            calculateManaToML (ml) {
                return 1600 * Math.pow(this.vocation.multipler, ml)
            },

            calculatePotions (potion) {
                const amount = this.mana / potion.mana
                const price = amount * potion.price
                const seconds = 60 * ((this.mana * 100 / (potion.mana * 60)) / 100)
                const time = seconds > 86400
                    ? moment.utc(seconds * 1000).format('DD HH:mm:ss')
                    : moment.utc(seconds * 1000).format('HH:mm:ss')

                return {
                    amount: amount,
                    price: price,
                    time: time
                }
            },
        }
    }
</script>