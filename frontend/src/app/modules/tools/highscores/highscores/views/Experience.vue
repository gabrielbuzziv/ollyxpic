<template>
    <experience :leaders="leaders" :highscores="highscores" :loading="loading" />
</template>

<script>
    import Experience from './types/Experience'
    import services from '../services'

    export default {
        components: { Experience },

        data () {
            return {
                loading: true,
                leaders: [],
                highscores: []
            }
        },

        watch: {
            '$route.params.vocation' () {
                this.load()
            }
        },

        methods: {
            load () {
                const vocation = this.$route.params.vocation ? this.$route.params.vocation : null

                this.loading = true
                services.getHighscores(vocation)
                    .then(response => {
                        this.loading = false
                        this.leaders = response.data.slice(0, 3)
                        this.highscores = response.data.slice(3)
                    })
                    .catch(() => this.loading = false)
            }
        },

        mounted () {
            this.load()
        }
    }
</script>