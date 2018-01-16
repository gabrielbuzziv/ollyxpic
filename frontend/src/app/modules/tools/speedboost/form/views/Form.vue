<template>
    <page-load id="speedboost">
        <page-title>
            <img :src="image_path_by_name('item', 'boots of haste')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Speed</h2>
                <span>Boost</span>
            </div>
        </page-title>

        <panel class="form">
            <div class="row">
                <div class="col-md-6">
                    <form-input placeholder="Level" v-model="player.level"/>
                </div>

                <div class="col-md-6">
                    <form-input placeholder="Speed Bonus" v-model="player.bonus"/>
                    <small class="helper-block">
                        Boh + Beetle Necklace = 22
                    </small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <el-radio class="radio" v-model="player.spell" :label="0">
                        No Spell
                    </el-radio>
                    <el-radio class="radio" v-model="player.spell" :label="1">
                        <el-tooltip content="Utani hur">
                            <img :src="image_path('spell', 9)" alt="">
                        </el-tooltip>
                    </el-radio>
                    <el-radio class="radio" v-model="player.spell" :label="2">
                        <el-tooltip content="Utani gran hur">
                            <img :src="image_path('spell', 38)" alt="">
                        </el-tooltip>
                    </el-radio>
                    <el-radio class="radio" v-model="player.spell" :label="3">
                        <el-tooltip content="Utani tempo hur">
                            <img :src="image_path('spell', 72)" alt="">
                        </el-tooltip>
                    </el-radio>
                </div>

                <div class="col-md-6">
                    <span class="player-speed" v-if="player.speed">
                        Your Speed is: <span>{{ player.speed }}</span>
                    </span>
                </div>
            </div>
        </panel>

        <el-tabs>
            <el-tab-pane label="All Tiles" class="row">
                <tile :tile="tile" :player="player" v-for="tile in tiles" :key="tile.id"/>
            </el-tab-pane>

            <!-- Deeper Banuta -->
            <el-tab-pane label="Deeper Banuta" class="row">
                <tile :tile="tile" :player="player" v-for="tile in getTilesFrom('banuta')" :key="tile.id"/>
            </el-tab-pane>

            <!-- Catacombs -->
            <el-tab-pane label="Catacombs" class="row">
                <tile :tile="tile" :player="player" v-for="tile in getTilesFrom('catacombs')" :key="tile.id"/>
            </el-tab-pane>

            <!-- Roshamuul -->
            <el-tab-pane label="Roshamuul" class="row">
                <tile :tile="tile" :player="player" v-for="tile in getTilesFrom('roshamuul')" :key="tile.id"/>
            </el-tab-pane>

            <!-- Roshamuul Prision -->
            <el-tab-pane label="Roshamuul Prision" class="row">
                <tile :tile="tile" :player="player" v-for="tile in getTilesFrom('prision')" :key="tile.id"/>
            </el-tab-pane>

            <!-- Lizard City -->
            <el-tab-pane label="Lizard City" class="row">
                <tile :tile="tile" :player="player" v-for="tile in getTilesFrom('lizardCity')" :key="tile.id"/>
            </el-tab-pane>
        </el-tabs>


    </page-load>
</template>

<script type="text/babel">
    import Tile from './Tile'

    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        components: { Tile },

        data () {
            return {
                tiles: [],
                player: {
                    level: 0,
                    bonus: 0,
                    spell: 0,
                    speed: 0
                }
            }
        },

        computed: {
            baseSpeed () {
                return parseInt(this.player.level) + 109;
            },

            spellSpeed () {
                const spell = parseInt(this.player.spell)
                switch (spell) {
                    case 1:
                        return (this.baseSpeed * 0.3) - 12
                    case 2:
                        return (this.baseSpeed * 0.7) - 28
                    case 3:
                        return (((this.player.level * 1.8) + 123.3) / 2)
                    case 4:
                        return 0
                    default:
                        return 0
                }
            },

            speed () {
                const bonus = parseInt(this.player.bonus) > 0 ? parseInt(this.player.bonus) : 0
                return Math.floor(this.baseSpeed + bonus + this.spellSpeed)
            }
        },

        watch: {
            'player.level' () {
                this.player.speed = this.speed
            },

            'player.bonus' () {
                this.player.speed = this.speed
            },

            'player.spell' () {
                this.player.speed = this.speed
            }
        },

        methods: {
            loadTiles () {
                services.fetchTiles()
                    .then(response => {
                        this.tiles = response.data.sort((a, b) => {
                            return a.friction - b.friction
                        })
                    })
            },

            getTilesFrom (location) {
                switch (location) {
                    case 'banuta':
                        return this.tiles.filter(tile => [499, 500, 516, 524, 543].indexOf(tile.object_id) > - 1)
                    case 'lizardCity':
                        return this.tiles.filter(tile => [9481, 9575].indexOf(tile.object_id) > - 1)
                    case 'roshamuul':
                        return this.tiles.filter(tile => [19825, 19550, 19558].indexOf(tile.object_id) > - 1)
                    case 'prision':
                        return this.tiles.filter(tile => [17544].indexOf(tile.object_id) > - 1)
                    case 'catacombs':
                        return this.tiles.filter(tile => [20712].indexOf(tile.object_id) > - 1)
                    default:
                        return this.tiles
                }
            }
        },

        mounted () {
            this.loadTiles()
        }
    }
</script>
