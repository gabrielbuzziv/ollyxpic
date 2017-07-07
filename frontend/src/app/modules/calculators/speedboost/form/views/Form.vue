<template>
    <page-load id="speedboost">
        <page-title>
            <img :src="item_path('boots-of-haste')" class="margin-right-5">
            Speed Boost
        </page-title>

        <div class="col-md-12">
            <panel class="form">
                <div class="row">
                    <div class="col-md-6">
                        <form-input placeholder="Level" v-model="level" />
                    </div>

                    <div class="col-md-6">
                        <form-input placeholder="Speed Bonus" v-model="bonus" />
                        <small class="helper-block">
                            Boh + Beetle Necklace = 22
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <el-radio class="radio" v-model="spell" :label="0">
                            No Spell
                        </el-radio>
                        <el-radio class="radio" v-model="spell" :label="1">
                            <el-tooltip content="Utani hur">
                                <img :src="image_path('spell', 9)" alt="">
                            </el-tooltip>
                        </el-radio>
                        <el-radio class="radio" v-model="spell" :label="2">
                            <el-tooltip content="Utani gran hur">
                                <img :src="image_path('spell', 38)" alt="">
                            </el-tooltip>
                        </el-radio>
                        <el-radio class="radio" v-model="spell" :label="3">
                            <el-tooltip content="Utani tempo hur">
                                <img :src="image_path('spell', 72)" alt="">
                            </el-tooltip>
                        </el-radio>
                        <el-radio class="radio" v-model="spell" :label="4">
                            <el-tooltip content="Utamo tempo san">
                                <img :src="image_path('spell', 95)" alt="">
                            </el-tooltip>
                        </el-radio>
                    </div>
                </div>
            </panel>

            <panel class="speed" v-if="level">
                Your base speed is <b>{{ baseSpeed }}</b> and with setted bonus is <b>{{ speed }}</b>
            </panel>

            <panel>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tile</th>
                            <th>Description</th>
                            <th class="text-center">Friction</th>
                            <th class="text-center">Speed to Boost</th>
                            <th class="text-center">Boosting?</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="tile , index in tiles">
                            <td>
                                <img :src="image_path('object', tile.object_id)" alt="">
                            </td>
                            <td>
                                {{ tile.name }}
                            </td>
                            <td class="text-center">
                                {{ tile.friction }}
                            </td>
                            <td class="text-center">
                                {{ getSpeedToBoost(tile.friction) }}
                            </td>
                            <td class="text-center">
                                <i class="mdi mdi-thumb-up text-success" v-if="canBoost(getSpeedToBoost(tile.friction))"></i>
                                <i class="mdi mdi-thumb-down text-danger" v-else></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        data () {
            return {
                tiles: [],
                level: 0,
                bonus: 0,
                spell: 0
            }
        },

        computed: {
            baseSpeed () {
                return parseInt(this.level) + 109;
            },

            spellSpeed () {
                const spell = parseInt(this.spell)
                switch (spell) {
                    case 1:
                        return (this.baseSpeed * 0.3) - 12
                    case 2:
                        return (this.baseSpeed * 0.7) - 28
                    case 3:
                        return (((this.level * 1.8) + 123.3) / 2)
                    case 4:
                        return 0
                    default:
                        return 0
                }
            },

            speed () {
                return Math.ceil(this.baseSpeed + parseInt(this.bonus) + this.spellSpeed);
            }
        },

        methods: {
            getSpeedToBoost (friction) {
                return Math.ceil((594 * friction) / 100)
            },

            canBoost (speed) {
                return this.speed >= speed ? true : false
            }
        },

        mounted () {
            services.fetchTiles()
                    .then(response => {
                        this.tiles = response.data.sort((a, b) => {
                            return a.friction - b.friction
                        })
                    })
        }
    }
</script>