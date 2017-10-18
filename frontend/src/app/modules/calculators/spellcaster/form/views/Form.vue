<template>
    <page-load id="spellcast">
        <page-title>
            <img :src="image_path('item', 117)" alt="">
            Spell Caster
            <span>Damage & Healing</span>
        </page-title>

        <div class="row" v-if="! loading">
            <div class="col-md-12">
                <panel class="form">
                    <div class="row">
                        <form-group :columns="12">
                            <el-select
                                    v-model="form.creature"
                                    placeholder="Creature"
                                    class="creature-select"
                                    :remote-method="getCreatures"
                                    remote
                                    filterable
                                    clearable
                                    default-first-option
                                    @change="selectCreature">
                                <el-option
                                        v-for="creature in form.creatures"
                                        :key="creature.name"
                                        :label="creature.title"
                                        :value="creature.id">
                                </el-option>
                            </el-select>
                        </form-group>
                    </div>

                    <div class="row">
                        <form-group :columns="6">
                            <form-input v-model="form.level" placeholder="Level"/>
                        </form-group>

                        <form-group :columns="6">
                            <form-input v-model="form.magiclevel" placeholder="Magic Level"/>
                        </form-group>

                        <form-group :columns="6" v-show="vocation == 'knight'">
                            <form-input v-model="form.melee" placeholder="Melee Skill"/>
                        </form-group>

                        <form-group :columns="6" v-show="vocation == 'knight'">
                            <form-input v-model="form.attack" placeholder="Weapon Damage"/>
                        </form-group>
                    </div>

                    <div class="row">
                        <form-group label="Vocation" :columns="6" class="text-center margin-top-10 margin-bottom-0">
                            <!--<el-radio v-model="form.vocation" :label="1">Knight</el-radio>-->
                            <!--<el-radio v-model="form.vocation" :label="2">Paladin</el-radio>-->
                            <el-radio v-model="form.vocation" :label="3">Druid</el-radio>
                            <el-radio v-model="form.vocation" :label="4">Sorcerer</el-radio>
                        </form-group>

                        <form-group label="Critical Damage" :columns="6" class="text-center margin-top-10 margin-bottom-0">
                            <el-radio v-model="form.critical" :label="0">0%</el-radio>
                            <el-radio v-model="form.critical" :label="10">10%</el-radio>
                            <el-radio v-model="form.critical" :label="25">25%</el-radio>
                            <el-radio v-model="form.critical" :label="50">50%</el-radio>
                        </form-group>
                    </div>
                </panel>
            </div>
        </div>

        <div class="row" v-if="creature">
            <div class="col-md-4">
                <panel class="creature-detail">
                    <img :src="image_path('creature', creature.id)" class="image">
                    <div class="status">
                        <h3>{{ creature.title }}</h3>
                    </div>
                </panel>
            </div>

            <div class="col-md-8">
                <panel class="creature-info">
                    <table class="simple margin-bottom-0">
                        <tbody>
                            <tr>
                                <th class="text-center" rowspan="2">
                                    <el-tooltip content="% of protection of element" placement="left">
                                        <i class="mdi mdi-information"></i>
                                    </el-tooltip>
                                </th>
                                <th class="text-center">Physical</th>
                                <th class="text-center">Holy</th>
                                <th class="text-center">Death</th>
                                <th class="text-center">Fire</th>
                                <th class="text-center">Energy</th>
                                <th class="text-center">Ice</th>
                                <th class="text-center">Earth</th>
                            </tr>
                            <tr>
                                <td class="text-center">{{ getDamageTaken(creature.physical) }} %</td>
                                <td class="text-center">{{ getDamageTaken(creature.holy) }} %</td>
                                <td class="text-center">{{ getDamageTaken(creature.death) }} %</td>
                                <td class="text-center">{{ getDamageTaken(creature.fire) }} %</td>
                                <td class="text-center">{{ getDamageTaken(creature.energy) }} %</td>
                                <td class="text-center">{{ getDamageTaken(creature.ice) }} %</td>
                                <td class="text-center">{{ getDamageTaken(creature.earth) }} %</td>
                            </tr>
                        </tbody>
                    </table>
                </panel>
            </div>
        </div>

        <div class="row" v-if="level">
            <div class="col-md-6" :class="{ 'col-md-12': index == 0 }" v-for="type, index in types">
                <panel>
                    <table class="simple margin-bottom-0">
                        <thead>
                            <tr>
                                <th width="60"></th>
                                <th class="text-center" width="130">Max</th>
                                <th class="text-center" width="130">Avg</th>
                                <th class="text-center" width="130">Min</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="spell in spells.filter(spell => spell.type == type)"
                                v-if="spell.vocations.indexOf(vocation) > -1 && spell.ml <= magiclevel">
                                <td class="text-center">
                                    <el-tooltip :content="spell.name" placement="top">
                                        <img :src="image_path(spell.image, spell.id)" alt="">
                                    </el-tooltip>
                                </td>
                                <td class="text-center damage">
                                    <img src="/src/assets/images/damage.png" alt="">
                                    {{ Math.ceil(getMaxDamage(spell)) }}
                                    <span class="critical" v-if="critical && spell.type != 'healing'">
                                        <img src="/src/assets/images/critical.png" alt="">
                                        {{ Math.ceil(getCriticalDamage(getMaxDamage(spell))) }}
                                    </span>
                                </td>
                                <td class="text-center damage">
                                    <img src="/src/assets/images/damage.png" alt="">
                                    {{ Math.ceil(getAvgDamage(spell)) }}
                                    <span class="critical" v-if="critical && spell.type != 'healing'">
                                        <img src="/src/assets/images/critical.png" alt="">
                                        {{ Math.ceil(getCriticalDamage(getAvgDamage(spell))) }}
                                    </span>
                                </td>
                                <td class="text-center damage">
                                    <img src="/src/assets/images/damage.png" alt="">
                                    {{ Math.ceil(getMinDamage(spell)) }}
                                    <span class="critical" v-if="critical && spell.type != 'healing'">
                                        <img src="/src/assets/images/critical.png" alt="">
                                        {{ Math.ceil(getCriticalDamage(getMinDamage(spell))) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                loading: false,
                creature: false,
                form: {
                    level: '',
                    magiclevel: '',
                    melee: '',
                    vocation: 3,
                    critical: 0,
                    creature: '',
                    creatures: [],
                    attack: 0,
                },
                spellsList: [
                    { id: 73, name: 'Wound Cleansing', ml: 0, element: 'heal', vocations: ['knight'], max: [7.95, 51], min: [4, 25], type: 'healing', image: 'spell', formula: 'normal' },
                    { id: 3, name: 'Light Healing', ml: 0, element: 'heal', vocations: ['paladin', 'sorcerer', 'druid'], max: [1.795, 11], min: [1.4, 8], type: 'healing', image: 'spell', formula: 'normal' },
                    { id: 23, name: 'Intense Healing', ml: 0, element: 'heal', vocations: ['sorcerer', 'druid'], max: [5.59, 35], min: [3.184, 20], type: 'healing', image: 'spell', formula: 'normal' },
                    { id: 37, name: 'Ultimate Healing', ml: 0, element: 'heal', vocations: ['sorcerer', 'druid'], max: [12.79, 79], min: [7.22, 44], type: 'healing', image: 'spell', formula: 'normal' },
                    { id: 35, name: 'Heal Friend', ml: 0, element: 'heal', vocations: ['druid'], max: 14, min: 10, type: 'healing', image: 'spell', formula: 'advanced' },
                    { id: 58, name: 'Mass Healing', ml: 0, element: 'heal', vocations: ['druid'], max: [10.43, 62], min: [5.7, 26], type: 'healing', image: 'spell', formula: 'normal' },
                    { id: 86, name: 'Divine Healing', ml: 0, element: 'heal', vocations: ['paladin'], max: 25, min: 18.5, type: 'healing', image: 'spell', formula: 'advanced' },
                    { id: 119, name: 'Salvation', ml: 0, element: 'heal', vocations: ['paladin'], max: [19, 50], min: [19, 50], type: 'healing', image: 'spell', formula: 'normal' },
                    { id: 117, name: 'Sudden Death Rune', ml: 15, element: 'death', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [7.395, 46], min: [4.605, 28], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2024, name: 'Avalanche Rune', ml: 4, element: 'ice', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [2.8, 17], min: [1.2, 7], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2019, name: 'Icicle Rune', ml: 4, element: 'ice', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [3, 18], min: [1.81, 10], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2010, name: 'Great Fireball Rune', ml: 4, element: 'fire', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [2.8, 17], min: [1.2, 7], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2009, name: 'Fireball Rune', ml: 4, element: 'fire', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [3, 18], min: [1.81, 10], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2017, name: 'Thunderstorm Rune', ml: 4, element: 'energy', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [2.6, 16], min: [1, 6], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2011, name: 'Heavy Magic Missile Rune', ml: 4, element: 'energy', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [1.59, 10], min: [0.81, 4], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2012, name: 'Light Magic Missile Rune', ml: 0, element: 'energy', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [0.81, 4], min: [0.4, 2], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2022, name: 'Stone Shower Rune', ml: 4, element: 'earth', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [2.6, 16], min: [1, 6], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2016, name: 'Stalagmite Rune', ml: 4, element: 'earth', vocations: ['knight', 'paladin', 'sorcerer', 'druid'], max: [1.59, 10], min: [0.81, 4], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 2018, name: 'Holy Missile Rune', ml: 4, element: 'holy', vocations: ['paladin'], max: [3.75, 24], min: [1.79, 11], type: 'rune', image: 'item', formula: 'normal' },
                    { id: 13, name: 'Death Strike', ml: 0, element: 'death', vocations: ['sorcerer', 'druid'], max: [2.203, 13], min: [1.403, 8], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 74, name: 'Ice Strike', ml: 0, element: 'ice', vocations: ['sorcerer', 'druid'], max: [2.203, 13], min: [1.403, 8], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 25, name: 'Flame Strike', ml: 0, element: 'fire', vocations: ['sorcerer', 'druid'], max: [2.203, 13], min: [1.403, 8], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 26, name: 'Energy Strike', ml: 0, element: 'energy', vocations: ['sorcerer', 'druid'], max: [2.203, 13], min: [1.403, 8], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 75, name: 'Terra strike', ml: 0, element: 'earth', vocations: ['sorcerer', 'druid'], max: [2.203, 13], min: [1.403, 8], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 129, name: 'Fire Wave', ml: 0, element: 'fire', vocations: ['sorcerer'], max: [2, 12], min: [1.25, 4], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 87, name: 'Ice Wave', ml: 0, element: 'ice', vocations: ['druid'], max: [2, 12], min: [0.81, 4], type: 'spell', image: 'spell', formula: 'normal' },
                    { id: 43, name: 'Energy Beam', ml: 0, element: 'energy', vocations: ['sorcerer'], max: 4, min: 2.5, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 53, name: 'Great Energy Beam', ml: 0, element: 'energy', vocations: ['sorcerer'], max: 7, min: 4, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 85, name: 'Divine Caldera', ml: 0, element: 'holy', vocations: ['paladin'], max: 6, min: 4, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 88, name: 'Terra Wave', ml: 0, element: 'earth', vocations: ['druid'], max: 7, min: 3.5, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 128, name: 'Ice Wave', ml: 0, element: 'ice', vocations: ['druid'], max: 10, min: 5, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 88, name: 'Strong Ice Wave', ml: 0, element: 'ice', vocations: ['druid'], max: 10, min: 4, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 59, name: 'Energy Wave', ml: 0, element: 'energy', vocations: ['sorcerer'], max: 9, min: 4.5, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 76, name: 'Rage of The Skies', ml: 0, element: 'energy', vocations: ['sorcerer'], max: 12, min: 5, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 80, name: 'Hell\'s Core', ml: 0, element: 'fire', vocations: ['sorcerer'], max: 14, min: 7, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 82, name: 'Wrath of Nature', ml: 0, element: 'earth', vocations: ['druid'], max: 10, min: 5, type: 'spell', image: 'spell', formula: 'advanced' },
                    { id: 83, name: 'Eternal Winter', ml: 0, element: 'ice', vocations: ['druid'], max: 12, min: 6, type: 'spell', image: 'spell', formula: 'advanced' },
                ]
            }
        },

        computed: {
            spells () {
                return this.spellsList.filter(spell => {
                    return spell.vocations.indexOf(this.vocation) > - 1 && this.magiclevel >= spell.ml
                })
            },

            types () {
                return this.spells.map(spell => spell.type).filter((type, index, self) => self.indexOf(type) === index)
            },

            level () {
                const level = parseInt(this.form.level)
                return level > 0 ? level : 0
            },

            magiclevel () {
                const magiclevel = parseInt(this.form.magiclevel)
                return magiclevel > 0 ? magiclevel : 0
            },

            critical () {
                const critical = parseInt(this.form.critical)
                return critical > 0 ? critical : 0
            },

            vocation () {
                switch (this.form.vocation) {
                    case 1:
                        return 'knight'
                    case 2:
                        return 'paladin'
                    case 3:
                        return 'druid'
                    case 4:
                        return 'sorcerer'
                }
            }
        },

        methods: {
            getMaxDamage (spell) {
                switch (spell.formula) {
                    case 'normal':
                        return this.calculateNormalDamage(spell, spell.max)
                    case 'advanced':
                        return this.calculateAdvancedDamage(spell, spell.max)
                    case 'healing':
                        return this.calculateNormalDamage(spell, spell.max)
                }
            },

            getAvgDamage (spell) {
                return (this.getMaxDamage(spell) + this.getMinDamage(spell)) / 2
            },

            getMinDamage (spell) {
                switch (spell.formula) {
                    case 'normal':
                        return this.calculateNormalDamage(spell, spell.min)
                    case 'advanced':
                        return this.calculateAdvancedDamage(spell, spell.min)
                    case 'healing':
                        return this.calculateNormalDamage(spell, spell.min)
                }
            },

            calculateNormalDamage (spell, constant) {
                let damage = (this.level * 0.2) + (this.magiclevel * constant[0]) + constant[1]

                if (this.creature && spell.type != 'healing') {
                    return damage * (this.creature[spell.element] / 100)
                }

                return damage
            },

            calculateAdvancedDamage (spell, constant) {
                let damage = (this.level / 5) + (this.magiclevel * constant)

                if (this.creature && spell.type != 'healing') {
                    return damage * (this.creature[spell.element] / 100)
                }

                return damage
            },

            getCriticalDamage (damage) {
                return damage * this.sumCritical()
            },

            sumCritical () {
                return this.critical > 0 ? 1 + (this.critical / 100) : 1
            },

            getDamageTaken (damage) {
                return 100 - damage;
            },

            getReducedDamage (damage, element) {
                if (this.creature) {
                    console.log(this.creature[element])
                }

                return 0
            },

            getCreatures: debounce (function (query) {
                if (! isEmpty(query)) {
                    services.getCreatures(query)
                            .then(response => {
                                this.form.creatures = response.data
                            })
                }
            }, 300),

            selectCreature (creature) {
                services.getCreature(creature)
                        .then(response => {
                            this.creature = response.data
                        })
                        .catch(error => {
                            this.creature = false
                        })
            }
        },
    }
</script>