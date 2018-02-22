<template>
    <experience :highscores="highscores" :loading="loading" />
</template>

<script>
    import Experience from './types/Experience'
    import services from '../services'

    export default {
        components: { Experience },

        data () {
            return {
                loading: true,
                highscores: []
            }
        },

        watch: {
            '$route.params.vocation' () {
                this.load()
            },

            '$route.params.world' () {
                this.load()
            }
        },

        methods: {
            load () {
                const vocation = this.$route.params.vocation ? this.$route.params.vocation : null
                const world = this.$route.params.world ? this.$route.params.world : null
                this.highscores = []

                this.loading = true
                services.getHighscores(vocation, world)
                    .then(response => {
                        this.loading = false
                        this.highscores = response.data.map((highscore, index) => {
                            return { ...highscore, rank: index + 1 }
                        })
                    })
                    .catch(() => this.loading = false)
            }
        },

        mounted () {
            this.load()
        }
    }
</script>