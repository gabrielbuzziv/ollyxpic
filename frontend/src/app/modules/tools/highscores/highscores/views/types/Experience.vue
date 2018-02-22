<template>
    <page-load class="experience no-padding" :loading="loading">
        <template slot="loading">
            <div class="highscore-loading">
                <div class="loader inline"></div>
                <div class="loading-text">
                    {{ loadingText }}
                    <small>This can take few seconds</small>
                </div>
            </div>
        </template>

        <panel class="highscores">
            <table class="table">
                <thead>
                    <tr>
                        <th>Character</th>
                        <th>Level</th>
                        <th class="hidden-sm hidden-xs">Experience</th>
                        <th class="hidden-sm hidden-xs">World</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <player :highscore="highscore" :key="highscore.id" v-for="highscore in highscores" />
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import Player from './ExperiencePlayer'

    export default {
        props: ["highscores", "loading"],

        components: { Player },

        computed: {
            loadingText () {
                let vocation = this.$route.params.vocation
                    ? this.$route.params.vocation
                    : null
                let world = this.$route.params.world
                    ? this.$route.params.world
                    : "all worlds"

                vocation =
                    vocation == "all" || vocation == null ? null : `${vocation}s`

                return vocation
                    ? `Loading the best ${vocation} from ${world}`
                    : `Loading the best players from ${world}`
            }
        },
    }
</script>