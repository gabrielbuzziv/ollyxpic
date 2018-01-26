<template>
    <div class="experience">
        <div class="row leaders">
            <div class="col-md-4" v-for="leader, index in leaders">
                <leader :leader="leader" :index="index"></leader>
            </div>
        </div>

        <panel class="highscores">
            <table class="table">
                <tbody>
                    <player :player="highscore"
                            :index="index"
                            :key="highscore.id"
                            v-for="highscore,index in highscores"/>
                </tbody>
            </table>
        </panel>
    </div>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import Leader from './Leader'
    import Player from './Player'
    import services from '../services'

    export default {
        components: { Leader, Player },

        data () {
            return {
                loading: true,
                leaders: [],
                highscores: []
            }
        },

        methods: {
            getPlayerOutfit (player) {
                return this.outfit('Summoner Outfits', 'female')
            }
        },

        mounted () {
            services.getHighscores()
                .then(response => {
                    this.loading = false
                    this.leaders = response.data.slice(0, 3)
                    this.highscores = response.data.slice(3)
                })
                .catch(() => this.loading = false)
        }
    }
</script>