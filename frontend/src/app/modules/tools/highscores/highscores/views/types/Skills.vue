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
                        <th>Skill</th>
                        <th>World</th>
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
    import Player from './SkillsPlayer'

    export default {
        props: ['highscores', 'loading'],

        components: { Player },

        computed: {
            loadingText () {
                let skill = this.$route.params.skill ? this.$route.params.skill : null
                let world = this.$route.params.world ? this.$route.params.world : 'all worlds'

                switch (this.$route.params.skill) {
                    case 'axe':
                        skill = 'axe fighter'
                        break
                    case 'club':
                        skill = 'club fighter'
                        break
                    case 'sword':
                        skill = 'sword fighter'
                        break
                    case 'distance':
                        skill = 'distance fighter'
                        break
                    case 'shielding':
                        skill = 'defender'
                        break
                }

                return skill
                    ? `Loading the best ${skill} from ${world}`
                    : `Loading the best players from ${world}`
            }
        },
    }
</script>