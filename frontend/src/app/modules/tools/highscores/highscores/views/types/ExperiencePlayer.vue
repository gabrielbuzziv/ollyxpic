<template>
    <tr>
        <td class="details" width="250">
            <div class="rank">{{ highscore.rank }}</div>

            <div class="name">
                <router-link :to="{ name: 'players', params: { name: highscore.name } }">
                    {{ highscore.name }}
                </router-link>
                <span class="vocation">{{ highscore.vocation }}</span>
            </div>
        </td>

        <td class="level">
            <div class="progress-label">
                <b>Level</b>
                <span>{{ highscore.level }}</span>
            </div>

            <el-progress :percentage="leftExperiencePercentage" :show-text="false"/>
        </td>

        <td class="world hidden-sm hidden-xs">
            <b>{{ highscore.experience.format() }}</b>
            <span>Experience</span>
        </td>

        <td class="world hidden-sm hidden-xs">
            <router-link :to="{ name: 'highscores.experience', params: { world: highscore.world.name } }">
            {{ highscore.world.name }}
            </router-link>
            <span>{{ highscore.world.type }}</span>
        </td>

        <td class="buttons text-right">
            <router-link :to="{ name: 'players', params: { name: highscore.name } }" class="btn btn-rounded">
                <i class="mdi mdi-chevron-right"></i>
            </router-link>
        </td>
    </tr>
</template>

<script>

    Number.prototype.format = function (n, x) {
        var re = "\\d(?=(\\d{" + (x || 3) + "})+" + (n > 0 ? "\\." : "$") + ")"
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, "g"), "$&.")
    }

    export default {
        props: ['highscore'],

        computed: {
            nextLevel () {
                return this.highscore.level + 1
            },

            leftExperience () {
                const nextLevelExperience = this.calculateExperience(this.nextLevel)
                return nextLevelExperience - this.highscore.experience
            },

            leftExperiencePercentage () {
                const level = this.calculateExperience(this.highscore.level)
                const nextLevel = this.calculateExperience(this.nextLevel)
                const experience = this.highscore.experience - level
                const total = nextLevel - level
                return parseInt(experience * 100 / total)
            }
        },

        methods: {
            calculateExperience (level) {
                return (50 * Math.pow(level - 1, 3) - 150 * Math.pow(level - 1, 2) + 400 * (level - 1)) / 3
            }
        }
    }
</script>