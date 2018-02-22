<template>
    <tr>
        <td class="details">
            <div class="rank">{{ highscore.rank }}</div>
            <div class="name">
                <router-link :to="{ name: 'players', params: { name: highscore.name } }">
                    {{ highscore.name }}
                </router-link>
                <span class="vocation">{{ highscore.vocation }}</span>
            </div>
        </td>
        <td class="skill">
            <b>{{ highscore.skill }}</b>
            <span>{{ skillLabel }}</span>
        </td>
        <td class="world">
            <router-link :to="{ name: 'highscores.skills', params: { skill: skill, world: highscore.world.name } }">
                {{ highscore.world.name }}
            </router-link>
            <span>{{ highscore.world.type }}</span>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['highscore'],

        computed: {
            skill () {
                return this.$route.params.skill
            },

            skillLabel () {
                switch (this.skill) {
                    case 'magic':
                        return 'Magic Level'
                    case 'axe':
                        return 'Axe Fighting'
                    case 'club':
                        return 'Club Fighting'
                    case 'sword':
                        return 'Sword Fighting'
                    case 'distance':
                        return 'Distance Fighting'
                    case 'shielding':
                        return 'Shielding'
                    case 'achievements':
                        return 'Achievements Points'
                    case 'loyalty':
                        return 'Loyalty Points'
                }
            },

            useExperience () {
                return this.skill == 'loyalty' || this.skill == 'achievements' ? true : false
            },

            valueProp () {
                return this.useExperience ? 'experience' : 'level'
            }
        }
    }
</script>