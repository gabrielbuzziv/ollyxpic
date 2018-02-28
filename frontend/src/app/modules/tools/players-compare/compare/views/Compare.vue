<template>
    <page-load id="compare">
        <page-title>
            <img :src="image_path_by_name('item', 'Golden Warrior Trophy')" class="margin-right-10 margin-top-5">
            <div class="title">
                <h2>Players Compare</h2>
                <span>Whos is the best?</span>
            </div>
        </page-title>

        <div :class="`col-md-${columns} compare`" v-for="player, index in players">
            <player :name="player" :index="index + 1" :player.sync="compares[compareOption(index)]" :players="compares" />
        </div>

        <div :class="`col-md-${columns} new`" v-if="! disabled">
            <panel class="addForm">
                <h4>{{ newTitle }}</h4>

                <div class="box">
                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               placeholder="Player name"
                               v-model="newPlayer"
                               @keypress.enter="addToCompare()">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" @click.prevent="addToCompare()">
                                <i class="mdi mdi-account-plus"></i>
                            </button>
                        </span>
                    </div>

                    <span class="separator">
                        or add one of the top 10 players...
                    </span>

                    <ul class="highscores">
                        <li v-for="highscore in highscores">
                            <button class="btn btn-default" @click.prevent="addToCompare(highscore.name)">
                                {{ highscore.name }}
                            </button>
                        </li>
                    </ul>
                </div>
            </panel>
        </div>
    </page-load>
</template>

<script>
    import Player from './Player.vue'
    import services from '../services'

    export default {
        components: { Player },

        data () {
            return {
                loading: true,
                newPlayer: '',
                highscores: [],
                compares: {
                    first: {},
                    second: {},
                    third: {},
                    fourth: {},
                },
            }
        },

        computed: {
            players () {
                return Object.values(this.$route.params).filter(player => player != null)
            },

            columns () {
                switch (this.players.length) {
                    case 0:
                        return 12
                    case 1:
                        return 6
                    case 2:
                        return 4
                    case 3:
                        return 3
                    case 4:
                        return 3
                }
            },

            newTitle () {
                switch (this.players.length) {
                    case 0:
                        return 'Add Player...'
                    case 1:
                        return 'Compare To...'
                    case 2:
                    case 3:
                        return 'Add another...'
                }
            },

            disabled () {
                return this.players.length == 4 ? true : false
            }
        },

        methods: {
            load () {
                this.loading = true
                services.getHighscores(10)
                    .then(response => {
                        this.highscores = response.data
                        this.loading = false
                    })
                    .catch(() => this.loading = false)
            },

            addToCompare (playerName = null) {
                if (playerName == null && (this.newPlayer == null || this.newPlayer == ''))
                    this.$message.error('You need to add a valid player to compare.')

                const newPlayer = playerName != null ? playerName : this.newPlayer
                const players = Object.values(this.$route.params).filter(player => player != null)
                let compare = []

                switch (players.length) {
                    case 0:
                        compare = { first: newPlayer }
                        break
                    case 1:
                        compare = { first: players[0], second: newPlayer }
                        break
                    case 2:
                        compare = { first: players[0], second: players[1], third: newPlayer }
                        break
                    case 3:
                        compare = { first: players[0], second: players[1], third: players[2], fourth: newPlayer }
                        break
                }

                this.newPlayer = ''
                this.$router.push({ name: 'compare.players', params: compare })
            },

            compareOption (index) {
                switch (index) {
                    case 0:
                        return 'first'
                    case 1:
                        return 'second'
                    case 2:
                        return 'third'
                    case 3:
                        return 'fourth'
                }
            }
        },

        mounted () {
            this.load()
        }
    }
</script>