<template>
    <page-load id="speedboost">
        <page-title>
            <img :src="item_path('boots-of-haste')" class="margin-right-5">
            Speed
            <span class="margin-left-45">Boost</span>
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
                    </div>
                </div>
            </panel>

            <panel class="speed" v-if="level">
                Your base speed is <b>{{ baseSpeed }}</b> and with setted bonus is <b>{{ speed }}</b>
            </panel>


            <div class="alert alert-warning">
                <p>All data was taken from tibia files.</p>
            </div>
        </div>

        <div class="col-md-8">
            <panel>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tile</th>
                            <!--<th>Description</th>-->
                            <th class="text-center">Friction</th>
                            <th class="text-center">Max Speed</th>
                            <th class="text-center">Boosting?</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="tile , index in tiles">
                            <td>
                                <img :src="tile_path(tile.object_id)" alt="">
                            </td>
                            <!--<td>-->
                                <!--{{ tile.name }}-->
                            <!--</td>-->
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

        <div class="col-md-4">
            <panel title="Catacomb">
                <table class="table margin-bottom-0 no-top-border">
                    <tbody>
                        <tr v-for="tile , index in catacomb">
                            <td width="70">
                                <img :src="tile_path(tile.object_id)" alt="">
                            </td>
                            <td class="text-center">
                                {{ getSpeedToBoost(tile.friction) }} speed
                            </td>
                            <td class="text-center">
                                <i class="mdi mdi-thumb-up text-success" v-if="canBoost(getSpeedToBoost(tile.friction))"></i>
                                <i class="mdi mdi-thumb-down text-danger" v-else></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>

            <panel title="Prison">
                <table class="table margin-bottom-0 no-top-border">
                    <tbody>
                        <tr v-for="tile , index in prison">
                            <td width="70">
                                <img :src="tile_path(tile.object_id)" alt="">
                            </td>
                            <td class="text-center">
                                {{ getSpeedToBoost(tile.friction) }} speed
                            </td>
                            <td class="text-center">
                                <i class="mdi mdi-thumb-up text-success" v-if="canBoost(getSpeedToBoost(tile.friction))"></i>
                                <i class="mdi mdi-thumb-down text-danger" v-else></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>

            <panel title="Roshamuul">
                <table class="table margin-bottom-0 no-top-border">
                    <tbody>
                        <tr v-for="tile , index in roshamuul">
                            <td width="70">
                                <img :src="tile_path(tile.object_id)" alt="">
                            </td>
                            <td class="text-center">
                                {{ getSpeedToBoost(tile.friction) }} speed
                            </td>
                            <td class="text-center">
                                <i class="mdi mdi-thumb-up text-success" v-if="canBoost(getSpeedToBoost(tile.friction))"></i>
                                <i class="mdi mdi-thumb-down text-danger" v-else></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>

            <panel title="Lizard City">
                <table class="table margin-bottom-0 no-top-border">
                    <tbody>
                        <tr v-for="tile , index in lizardCity">
                            <td width="70">
                                <img :src="tile_path(tile.object_id)" alt="">
                            </td>
                            <td class="text-center">
                                {{ getSpeedToBoost(tile.friction) }} speed
                            </td>
                            <td class="text-center">
                                <i class="mdi mdi-thumb-up text-success" v-if="canBoost(getSpeedToBoost(tile.friction))"></i>
                                <i class="mdi mdi-thumb-down text-danger" v-else></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </panel>

            <panel title="Banuta">
                <table class="table margin-bottom-0 no-top-border">
                    <tbody>
                        <tr v-for="tile , index in banuta">
                            <td width="70">
                                <img :src="tile_path(tile.object_id)" alt="">
                            </td>
                            <td class="text-center">
                                {{ getSpeedToBoost(tile.friction) }} speed
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
                spell: 0,
                newTile: {
                    name: '',
                    friction: 100,
                    object_id: 0
                },
                ids: []
            }
        },

        computed: {
            lizardCity () {
                const tiles = [9481, 9575]

                return this.tiles.filter(tile => {
                    return tiles.indexOf(tile.object_id) > -1
                })
            },

            banuta () {
                const tiles = [499, 500, 516, 524, 543]

                return this.tiles.filter(tile => {
                    return tiles.indexOf(tile.object_id) > -1
                })
            },

            roshamuul () {
                const tiles = [19825, 19550, 19558]

                return this.tiles.filter(tile => {
                    return tiles.indexOf(tile.object_id) > -1
                })
            },

            prison () {
                const tiles = [17544]

                return this.tiles.filter(tile => {
                    return tiles.indexOf(tile.object_id) > -1
                })
            },

            catacomb () {
                const tiles = [20712]

                return this.tiles.filter(tile => {
                    return tiles.indexOf(tile.object_id) > -1
                })
            },

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
                const bonus = parseInt(this.bonus) > 0 ? parseInt(this.bonus) : 0

                return Math.floor(this.baseSpeed + bonus + this.spellSpeed);
            }
        },

        methods: {
            getSpeedToBoost (friction) {
                switch (friction) {
                    case 70:
                        return 342
                    case 90:
                        return 500
                    case 95:
                        return 593
                    case 100:
                        return 592
                    case 110:
                        return 696
                    case 120:
                        return 813
                    case 121:
                        return 823
                    case 125:
                        return 876;
                    case 130:
                        return 944
                    case 140:
                        return 1092
                    case 150:
                        return 1258
                    case 160:
                        return 1443
                    case 170:
                        return 1652
                    case 180:
                        return 1886
                    case 200:
                        return 2445
                    case 250:
                        return 4557
                    default:
                        return 0
                }
            },

            canBoost (speed) {
                return this.speed >= speed ? true : false
            },

            loadTiles () {
                services.fetchTiles()
                        .then(response => {
                            this.tiles = response.data.sort((a, b) => {
                                return a.friction - b.friction
                            })
                        })
            }
        },

        mounted () {
            this.loadTiles()
        }
    }
</script>