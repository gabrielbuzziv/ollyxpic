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
                <el-radio class="vocation" v-model="skills.vocation" :label="index">
                    <div class="thumb">
                        <img :src="image_path_by_name('outfit', vocation.image)">
                    </div>

                    <span class="name">{{ vocation.name }}</span>
                </el-radio>
            </div>
        </div>

        <panel class="form">
            <div class="row">
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" placeholder="From" v-model="skills.from">
                </div>

                <div class="form-group col-md-4">
                    <span class="slider-label">
                        <span class="left">
                            Next ML in
                        </span>

                        <span class="right">
                            {{ (percentage * 100).toFixed() }}%
                        </span>
                    </span>

                    <el-slider
                            v-model="skills.percentage"
                            :min="0"
                            :max="99"
                            :step="1"
                            :show-tooltip="false"/>
                </div>

                <div class="form-group col-md-4">
                    <input type="text" class="form-control" placeholder="To" v-model="skills.to">
                </div>
            </div>
        </panel>

        <panel class="form-advanced">
            <div class="row">
                <div class="col-md-4">
                    <h4>Bonus</h4>

                    <el-checkbox v-model="advanced.double">Double EXP</el-checkbox>
                </div>

                <div class="col-md-4">
                    <h4>Loyality</h4>

                    <el-select v-model="advanced.loyality">
                        <el-option v-for="loyality, index in loyalities" :value="loyality.value" :label="loyality.label" :key="index"/>
                    </el-select>
                </div>
            </div>
        </panel>

        <div class="alert alert-warning margin-top-20" v-if="mana && advanced.loyality">
            <h4>Loyality</h4>

            <p>Your magic level without loyality is <b>{{ player.from }}</b> and the desire magic level is <b>{{ player.to }}</b>.</p>
        </div>

        <div class="alert alert-info margin-top-20" v-if="mana">
            You need to use: <b>{{ mana.format() }}</b> of your mana to go from magic level {{ skills.from
            }} to magic level {{ skills.to }}.
        </div>

        <div class="row potions" v-if="mana">
            <div class="col-md-6" v-for="potion in validPotions">
                <panel class="potion">
                    <header>
                        <div class="thumb">
                            <img :src="image_path('item', potion.id)">
                        </div>
                        <span class="name">{{ potion.name }}</span>

                        <span class="price">
                            <span class="input-group">
                                <input type="text" class="form-control" v-model="potion.price">
                                <span class="input-group-addon">gp</span>
                            </span>
                        </span>
                    </header>

                    <div class="info">
                        <span class="amount">
                            <i class="mdi mdi-menu"></i>
                            {{ calculatePotions(potion).amount.format() }} potions
                        </span>
                        <span class="price">
                            <i class="mdi mdi-coin"></i>
                            {{ calculatePotions(potion).price.format() }} gp
                        </span>
                        <span class="time">
                            <i class="mdi mdi-clock"></i>
                            {{ calculatePotions(potion).time }}
                        </span>
                    </div>
                </panel>
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
                    {
                        name: 'Druid',
                        active: true,
                        multiplier: 1.1,
                        potions: ['mp', 'smp', 'gmp', 'ump'],
                        image: 'Druid Outfits',
                        regen: [2 / 3, 2 / 2]
                    },
                    {
                        name: 'Sorcerer',
                        active: false,
                        multiplier: 1.1,
                        potions: ['mp', 'smp', 'gmp', 'ump'],
                        image: 'Mage Outfits',
                        regen: [2 / 3, 2 / 2]
                    },
                    {
                        name: 'Knight',
                        active: false,
                        multiplier: 3,
                        potions: ['mp'],
                        image: 'Warrior Outfits',
                        regen: [2 / 6, 2 / 6]
                    },
                    {
                        name: 'Paladin',
                        active: false,
                        multiplier: 1.4,
                        potions: ['mp', 'smp', 'gsp', 'usp'],
                        image: 'Hunter Outfits',
                        regen: [2 / 4, 2 / 3]
                    },
                ],

                potions: [
                    { id: 981, slug: 'mp', name: 'Mana Potion', mana: 100, price: 50 },
                    { id: 974, slug: 'smp', name: 'Strong Mana Potion', mana: 150, price: 80 },
                    { id: 975, slug: 'gmp', name: 'Great Mana Potion', mana: 200, price: 120 },
                    { id: 2883, slug: 'ump', name: 'Ultimate Mana Potion', mana: 500, price: 350 },
                    { id: 1062, slug: 'gsp', name: 'Great Spirit Potion', mana: 150, price: 190 },
                    { id: 2884, slug: 'usp', name: 'Ultimate Spirit Potion', mana: 215, price: 350 },
                ],

                skills: {
                    vocation: 0,
                    from: null,
                    precentage: 0,
                    to: null,
                },

                advanced: {
                    double: false,
                    loyality: 0
                },

                loyalities: [
                    { label: 'No Loyality', value: 0 },
                    { label: '5%', value: 0.05 },
                    { label: '10%', value: 0.10 },
                    { label: '15%', value: 0.15 },
                    { label: '20%', value: 0.20 },
                    { label: '25%', value: 0.25 },
                    { label: '30%', value: 0.30 },
                    { label: '35%', value: 0.35 },
                    { label: '40%', value: 0.40 },
                    { label: '45%', value: 0.45 },
                    { label: '50%', value: 0.50 },
                ]
            }
        },

        computed: {
            vocation () {
                return this.vocations[this.skills.vocation]
            },

            validPotions () {
                return this.potions.filter(potion => this.vocation.potions.indexOf(potion.slug) > - 1)
            },

            percentage () {
                return (100 - parseInt(this.skills.percentage)) / 100
            },

            mana () {
                const from = this.advanced.loyality ? this.player.from : parseInt(this.skills.from)
                const to = this.advanced.loyality ? this.player.to : parseInt(this.skills.to)
                const percentage = this.advanced.loyality ? this.player.percentage : this.percentage
                const levels = Math.abs(Math.ceil(from - to))

                let mana = typeof percentage == 'object'
                    ? this.calculateManaToML(from) * percentage.from + this.calculateManaToML(to) * percentage.to
                    : this.calculateManaToML(from) * percentage

                if (levels > 1) {
                    for (let i = 1; i < levels; i ++) {
                        mana += this.calculateManaToML(from + i)
                    }
                }

                return from > 0 && to > 0
                    ? typeof mana == 'number'
                        ? parseInt(mana.toFixed())
                        : 0
                    : 0
            },

            player () {
                const ml = parseInt(this.skills.from) + (parseInt(this.skills.percentage) / 100)
                const mana = this.calculateTotalMana(ml)
                const rawMana = this.calculateManaWithoutLoyality(mana)
                const rawML = this.calculateMLFromTotalMana(rawMana)
                const manaTo = this.calculateTotalMana(parseInt(this.skills.to))
                const rawManaTo = this.calculateManaWithoutLoyality(manaTo)
                const rawMLTo = this.calculateMLFromTotalMana(rawManaTo)
                let from = Math.floor(rawML)
                let to = Math.floor(rawMLTo)
                let percentage = from == to
                    ? this.percentage
                    : {
                        from: Math.abs(Math.abs(rawML - from) - 1),
                        to: Math.abs(rawMLTo - to)
                    }

                return this.advanced.loyality
                    ? { from, to, percentage }
                    : mana
            },
        },

        methods: {
            calculateManaToML (ml) {
                return 1600 * Math.pow(this.vocation.multiplier, ml)
            },

            calculateTotalMana (ml) {
                return (1600 * (Math.pow(this.vocation.multiplier, ml) - 1)) / (this.vocation.multiplier - 1)
            },

            calculateManaWithoutLoyality (mana) {
                return (mana * 100) / ((this.advanced.loyality + 1) * 100)
            },

            calculateMLFromTotalMana (mana) {
                return Math.log((((mana * (this.vocation.multiplier - 1)) / 1600) + 1)) / Math.log(this.vocation.multiplier)
            },

            calculatePotions (potion) {
                let mana = this.advanced.double ? this.mana / 2 : this.mana
                let amount = mana / potion.mana
                let seconds = 60 * ((mana * 100 / (potion.mana * 60)) / 100)
                let price = amount * potion.price
                let time = seconds > 31104000
                    ? `Over an year`
                    : seconds > 86400
                        ? moment.utc(seconds * 1000).format('DD [day(s)] HH [hors(s)] mm [minute(s)]')
                        : moment.utc(seconds * 1000).format('HH [hors(s)] mm [minute(s)]')

                return {
                    amount: amount,
                    price: price,
                    time: time
                }
            },
        }
    }
</script>