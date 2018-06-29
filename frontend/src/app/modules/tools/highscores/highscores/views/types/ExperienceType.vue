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
                let type = this.$route.params.type
                    ? this.$route.params.type
                    : null

                let typeLabel = null;

                switch (type) {
                    case 'retro':
                        typeLabel = 'Retro Open PvP'
                        break;
                    case 'hardcore':
                        typeLabel = 'Hardcore PvP'
                        break;
                    case 'retro-hardcore':
                        typeLabel = 'Retro Hardcore PvP'
                        break;
                    case 'npvp':
                        typeLabel = 'Optional PvP'
                        break;
                    case 'pvp':
                        typeLabel = 'Open PvP'
                        break;
                    default:
                        typeLabel = null;
                }

                return `Loading the best player from ${typeLabel} worlds`
            }
        },
    }
</script>