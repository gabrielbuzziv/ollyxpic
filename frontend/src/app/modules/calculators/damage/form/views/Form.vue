<template>
    <page-load id="damage">
        <page-title>
            <img :src="item_path('sudden-death-rune')" alt="">
            Damage
            <span>Something here</span>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <panel class="form">
                    <div class="row">
                        <form-group :columns="3">
                            <form-input v-model="form.level" placeholder="Level"/>
                        </form-group>

                        <form-group :columns="3">
                            <form-input v-model="form.magiclevel" placeholder="Magic Level"/>
                        </form-group>

                        <form-group :columns="3">
                            <form-input v-model="form.melee" placeholder="Melee Skill"/>
                        </form-group>

                        <form-group :columns="3">
                            <form-input v-model="form.attack" placeholder="Weapon Damage"/>
                        </form-group>
                    </div>

                    <div class="row margin-top-20">
                        <form-group label="Vocation" class="margin-bottom-0" :columns="5">
                            <el-radio v-model="form.vocation" :label="1">Knight</el-radio>
                            <el-radio v-model="form.vocation" :label="2">Paladin</el-radio>
                            <el-radio v-model="form.vocation" :label="3">Druid</el-radio>
                            <el-radio v-model="form.vocation" :label="4">Sorcerer</el-radio>
                        </form-group>

                        <form-group label="Critical Damage" class="margin-bottom-0" :columns="4">
                            <el-radio v-model="form.critical" :label="0">0%</el-radio>
                            <el-radio v-model="form.critical" :label="10">10%</el-radio>
                            <el-radio v-model="form.critical" :label="25">25%</el-radio>
                            <el-radio v-model="form.critical" :label="50">50%</el-radio>
                        </form-group>

                        <form-group class="margin-bottom-0" :columns="3" v-if="form.creatures">
                            <el-select
                                    v-model="form.creature"
                                    placeholder="Creature"
                                    filterable
                                    clearable
                                    default-first-option>
                                <el-option
                                        v-for="creature in form.creatures"
                                        :key="creature.name"
                                        :label="creature.title"
                                        :value="creature">
                                </el-option>
                            </el-select>
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
                        <small class="health">{{ creature.health }} hitpoints</small>
                    </div>
                </panel>
            </div>

            <div class="col-md-8">
                <panel class="creature-info">
                    <table class="simple margin-bottom-0">
                        <thead>
                            <tr>
                                <th class="text-center">Physical</th>
                                <th class="text-center">Holy</th>
                                <th class="text-center">Death</th>
                                <th class="text-center">Fire</th>
                                <th class="text-center">Energy</th>
                                <th class="text-center">Ice</th>
                                <th class="text-center">Earth</th>
                            </tr>
                        </thead>

                        <tbody>
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
            <div class="col-md-6" v-for="type in types">
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
                                <td>
                                    <el-tooltip :content="spell.name" placement="top">
                                        <img :src="image_path(spell.image, spell.id)" alt="">
                                    </el-tooltip>
                                </td>
                                <td class="text-center damage">
                                    <img src="/src/assets/images/damage.png" alt="">
                                    {{ Math.ceil(getMaxDamage(spell)) }}
                                    <span class="critical" v-if="critical">
                                        <img src="/src/assets/images/critical.png" alt="">
                                        {{ Math.ceil(getCriticalDamage(getMaxDamage(spell))) }}
                                    </span>
                                </td>
                                <td class="text-center damage">
                                    <img src="/src/assets/images/damage.png" alt="">
                                    {{ Math.ceil(getAvgDamage(spell)) }}
                                    <span class="critical" v-if="critical">
                                        <img src="/src/assets/images/critical.png" alt="">
                                        {{ Math.ceil(getCriticalDamage(getAvgDamage(spell))) }}
                                    </span>
                                </td>
                                <td class="text-center damage">
                                    <img src="/src/assets/images/damage.png" alt="">
                                    {{ Math.ceil(getMinDamage(spell)) }}
                                    <span class="critical" v-if="critical">
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
                searching: true,
                form: {
                    level: '',
                    magiclevel: '',
                    melee: '',
                    vocation: 1,
                    critical: 0,
                    creature: '',
                    creatures: [],
                    attack: 0,
                },
                spellsList: [
                    {
                        id: 117,
                        name: 'Sudden Death Rune',
                        ml: 15,
                        element: 'death',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [7.395, 46],
                        min: [4.605, 28],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2024,
                        name: 'Avalanche Rune',
                        ml: 4,
                        element: 'ice',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [2.8, 17],
                        min: [1.2, 7],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2019,
                        name: 'Icicle Rune',
                        ml: 4,
                        element: 'ice',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [3, 18],
                        min: [1.81, 10],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2010,
                        name: 'Great Fireball Rune',
                        ml: 4,
                        element: 'fire',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [2.8, 17],
                        min: [1.2, 7],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2009,
                        name: 'Fireball Rune',
                        ml: 4,
                        element: 'fire',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [3, 18],
                        min: [1.81, 10],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2017,
                        name: 'Thunderstorm Rune',
                        ml: 4,
                        element: 'energy',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [2.6, 16],
                        min: [1, 6],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2011,
                        name: 'Heavy Magic Missile Rune',
                        ml: 4,
                        element: 'energy',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [1.59, 10],
                        min: [0.81, 4],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2012,
                        name: 'Light Magic Missile Rune',
                        ml: 0,
                        element: 'energy',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [0.81, 4],
                        min: [0.4, 2],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2022,
                        name: 'Stone Shower Rune',
                        ml: 4,
                        element: 'earth',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [2.6, 16],
                        min: [1, 6],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2016,
                        name: 'Stalagmite Rune',
                        ml: 4,
                        element: 'earth',
                        vocations: ['knight', 'paladin', 'sorcerer', 'druid'],
                        max: [1.59, 10],
                        min: [0.81, 4],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 2018,
                        name: 'Holy Missile Rune',
                        ml: 4,
                        element: 'HOLY',
                        vocations: ['paladin'],
                        max: [3.75, 24],
                        min: [1.79, 11],
                        type: 'rune',
                        image: 'item'
                    },
                    {
                        id: 13,
                        name: 'Death Strike',
                        ml: 0,
                        element: 'death',
                        vocations: ['sorcerer', 'druid'],
                        max: [2.203, 13],
                        min: [1.403, 8],
                        type: 'spell',
                        image: 'spell'
                    },
                    {
                        id: 74,
                        name: 'Ice Strike',
                        ml: 0,
                        element: 'ice',
                        vocations: ['sorcerer', 'druid'],
                        max: [2.203, 13],
                        min: [1.403, 8],
                        type: 'spell',
                        image: 'spell'
                    },
                    {
                        id: 25,
                        name: 'Flame Strike',
                        ml: 0,
                        element: 'fire',
                        vocations: ['sorcerer', 'druid'],
                        max: [2.203, 13],
                        min: [1.403, 8],
                        type: 'spell',
                        image: 'spell'
                    },
                    {
                        id: 26,
                        name: 'Energy Strike',
                        ml: 0,
                        element: 'energy',
                        vocations: ['sorcerer', 'druid'],
                        max: [2.203, 13],
                        min: [1.403, 8],
                        type: 'spell',
                        image: 'spell'
                    },
                    {
                        id: 75,
                        name: 'Terra strike',
                        ml: 0,
                        element: 'earth',
                        vocations: ['sorcerer', 'druid'],
                        max: [2.203, 13],
                        min: [1.403, 8],
                        type: 'spell',
                        image: 'spell'
                    }
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

            creature () {
                const creature = this.form.creature
                return ! isEmpty(creature) ? creature : false
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
                let damage = (this.level * 0.2) + (this.magiclevel * spell.max[0]) + spell.max[1]

                if (this.creature) {
                    return damage * (this.creature[spell.element] / 100)
                }

                return damage
            },

            getAvgDamage (spell) {
                let damage = (this.getMaxDamage(spell) + this.getMinDamage(spell)) / 2

                if (this.creature) {
                    return damage * (this.creature[spell.element] / 100)
                }

                return damage
            },

            getMinDamage (spell) {
                let damage = (this.level * 0.2) + (this.magiclevel * spell.min[0]) + spell.min[1]

                if (this.creature) {
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
                console.log('none')
                if (this.creature) {
                    console.log('haha')
                    console.log(this.creature[element])
                }

                return 0
            }
        },

        mounted () {
            services.getCreatures()
                    .then(response => {
                        this.form.creatures = response.data
                        this.searching      = false
                    })
                    .catch(error => {
                        this.searching = false
                    })
        }
    }
</script>