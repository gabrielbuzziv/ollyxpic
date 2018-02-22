<template>
    <skills :highscores="highscores" :loading="loading" />
</template>

<script>
    import Skills from './types/Skills'
    import services from '../services'

    export default {
        components: { Skills },

        data () {
            return {
                loading: true,
                highscores: []
            }
        },

        watch: {
            '$route.params.skill' () {
                this.load()
            },

            '$route.params.world' () {
                this.load()
            }
        },

        methods: {
            load () {
                const skill = this.$route.params.skill ? this.$route.params.skill : null
                const world = this.$route.params.world ? this.$route.params.world : null
                this.highscores = []

                this.loading = true
                services.getSkillHighscores(skill, world)
                    .then(response => {
                        this.loading = false
                        this.highscores = response.data.map((highscore, index) => {
                            return { ...highscore,  rank: index + 1 }
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