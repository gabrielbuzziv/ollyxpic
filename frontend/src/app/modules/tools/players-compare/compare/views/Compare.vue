<template>
    <page-load id="compare">
        <page-title>
            <div class="pull-right form">
                <input type="text"
                       class="form-control"
                       placeholder="Player to Compare"
                       :disabled="disabled"
                       v-model="newPlayer"
                       @keypress.enter="addToCompare()">
                <small class="helper-block">Press "enter" to add.</small>
            </div>

            <img :src="image_path_by_name('item', 'old parchment')" alt="">
            <div class="title">
                <h2>Compare</h2>
                <span>Players</span>
            </div>
        </page-title>

        <div class="alert alert-warning">
            <p>
                This tool is a prototype, give us your suggestion.
                The loading of players take some seconds.
            </p>
        </div>

        <div :class="`col-md-${columns} compare`" v-for="player, index in players">
            <player :name="player" :index="index + 1"/>
        </div>
    </page-load>
</template>

<script>
    import Player from './Player'

    export default {
        components: { Player },

        data () {
            return {
                newPlayer: ''
            }
        },

        computed: {
            players () {
                return Object.values(this.$route.params).filter(player => player != null)
            },

            columns () {
                switch (this.players.length) {
                    case 1:
                        return 12
                    case 2:
                        return 6
                    case 3:
                        return 4
                    case 4:
                        return 3
                }
            },

            disabled () {
                return this.players.length == 4 ? true : false
            }
        },

        methods: {
            addToCompare () {
                let players = Object.values(this.$route.params).filter(player => player != null)
                let params = {}

                players.push(this.newPlayer)

                switch (players.length) {
                    case 4:
                        params = { first: players[0], second: players[1], third: players[2], fourth: players[3] }
                        break
                    case 3:
                        params = { first: players[0], second: players[1], third: players[2] }
                        break
                    case 2:
                        params = { first: players[0], second: players[1] }
                        break
                }

                this.newPlayer = ''
                this.$router.push({ name: 'compare.players', params: params })
            }
        },
    }
</script>