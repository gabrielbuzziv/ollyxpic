<template>
    <experience :highscores="highscores" :loading="loading" />
</template>

<script>
    import Experience from './types/ExperienceType'
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
            '$route.params.type' () {
                this.load()
            },
        },

        methods: {
            load () {
                const type = this.$route.params.type ? this.$route.params.type : null
                this.highscores = []

                this.loading = true
                services.getHighscores(null, null, type, 50)
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