<template>
    <panel class="leader">
        <header>

            <div class="title">
                <div class="rank-badge">
                    {{ index + 1 }}
                </div>

                <span class="left">
                    <span class="name">
                        <router-link :to="{ name: 'tools.players', params: { name: leader.name } }">
                            {{ leader.name }}
                        </router-link>
                    </span>
                    <span class="vocation">{{ leader.vocation }}</span>
                </span>

                <span class="right">
                    <span class="level">
                        <span>Level</span>
                        <b>{{ leader.level.format() }}</b>
                    </span>
                    <el-progress :percentage="experiencePercentage" :show-text="false" :stroke-width="10"/>
                </span>
            </div>
        </header>

        <div class="world">
            <b>{{ leader.world.name }}</b>
            <span>{{ leader.world.type }}</span>
        </div>

        <div class="advances">
            <div v-if="lastWeekExperience >= 0">
                <b>+{{ lastWeekExperience.format() }}</b>
                <span>Last 7 days experience</span>
            </div>

            <div v-else>
                <b class="loose">{{ lastWeekExperience.format() }}</b>
                <span>Last 7 days experience</span>
            </div>
        </div>

        <router-link :to="{ name: 'tools.players', params: { name: leader.name } }" class="btn btn-xs btn-rounded margin-top-20">
            <i class="mdi mdi-chart-bar margin-right-10"></i>
            Statistics
        </router-link>
    </panel>
</template>

<script>
    export default {
        props: ['leader', 'index'],

        data () {
            return {
                details: false
            }
        },

        computed: {
            advances () {
                return this.leader && this.leader.week_experience
                    ? this.leader.week_experience.slice().sort((a, b) => a.id - b.id).map((experience, index) => {
                        const advance = index > 0
                            ? experience.experience - this.leader.week_experience[index - 1].experience
                            : 0

                        return { ...experience, ...{ advance } }
                    })
                    : []
            },

            current () {
                return this.advances.slice().sort((a, b) => b.id - a.id)[0]
            },

            lastWeekExperience () {
                return this.advances.reduce((carry, advance) => carry + advance.advance, 0)
            },

            experiencePercentage () {
                const advance = this.current

                if (this.advances.length) {
                    const nextLevel = advance.level + 1
                    const nextLevelExp = ((50 * Math.pow((nextLevel - 1), 3)) - (150 * Math.pow((nextLevel - 1), 2)) + (400 * (nextLevel - 1))) / 3
                    const currentExp = advance.experience
                    const expToNextLevel = 50 * Math.pow(advance.level, 2) - 150 * advance.level + 200
                    const expLeft = nextLevelExp - currentExp
                    const currentLeveledExp = expToNextLevel - expLeft
                    const percentage = parseInt((currentLeveledExp * 100) / expToNextLevel)

                    return this.advances
                        ? percentage
                        : 0
                }

                return 0
            }
        },
    }
</script>