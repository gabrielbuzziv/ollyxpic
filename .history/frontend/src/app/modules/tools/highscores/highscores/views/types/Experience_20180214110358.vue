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
            <el-table
                    :data="highscoresWithAdvances"
                    :default-sort="{ prop: 'experience', order: 'descending' }">
                <el-table-column prop="name" label="Character" class-name="details" width="300">
                    <template slot-scope="scope">
                        <div class="rank">
                            {{ scope.row.rank }}
                        </div>

                        <div class="name">
                            <router-link :to="{ name: 'players', params: { name: scope.row.name } }">
                                {{ scope.row.name }}
                            </router-link>

                            <span class="vocation">
                                {{ scope.row.vocation }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column prop="experience" label="Level" class-name="level" sortable>
                    <template slot-scope="scope">
                        <div class="progress-label">
                            <b>Level</b>
                            <span>{{ scope.row.level }}</span>
                        </div>

                        <el-tooltip placement="right">
                            <template slot="content">
                                <span class="margin-bottom-5 block">
                                    {{ scope.row.name }} has {{ scope.row.experience.format() }}
                                    experience points.
                                </span>

                                <span>
                                    {{ scope.row.name }} need
                                    {{ getExperience(scope.row).leftExperience.format() }}
                                    ({{ getExperience(scope.row).percentageNextLevel }}%)
                                    experience to level {{ scope.row.level + 1 }}.
                                </span>
                            </template>

                            <el-progress
                                    :percentage="getExperience(scope.row).percentage"
                                    :show-text="false"/>
                        </el-tooltip>
                    </template>
                </el-table-column>

                <el-table-column prop="experience" label="Experience" class-name="world" sortable>
                    <template slot-scope="scope">
                        <b>{{ scope.row.experience.format() }}</b>
                        <span>Experience</span>
                    </template>
                </el-table-column>

                <el-table-column prop="world_id" class-name="world">
                    <template slot-scope="scope">
                        <router-link :to="{ name: 'highscores.experience', params: { world: scope.row.world.name } }">
                            {{ scope.row.world.name }}
                        </router-link>
                        <span>{{ scope.row.world.type }}</span>
                    </template>
                </el-table-column>

                <el-table-column class-name="buttons" align="right">
                    <template slot-scope="scope">
                        <router-link :to="{ name: 'players', params: { name: scope.row.name } }"
                                     class="btn btn-rounded">
                            <i class="mdi mdi-eye margin-right-5"></i>
                            Details
                        </router-link>
                    </template>
                </el-table-column>

                <!--<el-table-column prop="month" label="Last 30 days" class-name="advance" align="center" sortable>-->
                <!--<template slot-scope="scope">-->
                <!--<b :class="{ 'loose': isLoose(scope.row.month) }">{{ scope.row.month | experience }}</b>-->
                <!--</template>-->
                <!--</el-table-column>-->

                <!--<el-table-column prop="week" label="Last 7 days" class-name="advance" align="center" sortable>-->
                <!--<template slot-scope="scope">-->
                <!--<b :class="{ 'loose': isLoose(scope.row.week) }">{{ scope.row.week | experience }}</b>-->
                <!--</template>-->
                <!--</el-table-column>-->

                <!--<el-table-column prop="day" label="Yesterday" class-name="advance" align="center" sortable>-->
                <!--<template slot-scope="scope">-->
                <!--<b :class="{ 'loose': isLoose(scope.row.day) }">{{ scope.row.day | experience }}</b>-->
                <!--</template>-->
                <!--</el-table-column>-->
            </el-table>
        </panel>
    </page-load>
</template>

<script>
Number.prototype.format = function(n, x) {
    var re = "\\d(?=(\\d{" + (x || 3) + "})+" + (n > 0 ? "\\." : "$") + ")"
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, "g"), "$&.")
}

export default {
    props: ["highscores", "loading"],

    computed: {
        highscoresWithAdvances() {
            return this.highscores.map((highscore, index) => {
                //                    const month = this.getMonth(highscore)
                //                    const week = this.getWeek(highscore)
                //                    const day = this.getDay(highscore)
                //
                //                    return { ...highscore, month, week, day, rank: index + 1 }
                return { ...highscore, rank: index + 1 }
            })
        },

        loadingText() {
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

    filters: {
        experience(data) {
            return data >= 0 ? `+${data.format()}` : data.format()
        }
    },

    methods: {
        getExperience(advance) {
            const nextLevel = advance.level + 1
            const nextLevelExp =
                (50 * Math.pow(nextLevel - 1, 3) -
                    150 * Math.pow(nextLevel - 1, 2) +
                    400 * (nextLevel - 1)) /
                3
            const currentExp = advance.experience
            const expToNextLevel =
                50 * Math.pow(advance.level, 2) - 150 * advance.level + 200
            const expLeft = nextLevelExp - currentExp
            const currentLeveledExp = expToNextLevel - expLeft
            const percentage = parseInt(
                currentLeveledExp * 100 / expToNextLevel
            )
            const percentageNextLevel = 100 - percentage

            return advance
                ? { leftExperience: expLeft, percentage, percentageNextLevel }
                : { leftExperience: 0, percentage: 0 }
        }

        //            getAdvances (player) {
        //                return player.week_experience
        //                    ? player.week_experience.slice().sort((a, b) => a.id - b.id).map((experience, index) => {
        //                        const advance = index > 0
        //                            ? experience.experience - player.week_experience[index - 1].experience
        //                            : 0
        //
        //                        return { ...experience, ...{ advance } }
        //                    })
        //                    : 0
        //            },
        //
        //            getMonth (player) {
        //                return this.getAdvances(player).slice().sort((a, b) => b.id - a.id)
        //                    .reduce((carry, advance) => carry + advance.advance, 0)
        //            },
        //
        //            getWeek (player) {
        //                return this.getAdvances(player).slice(0, 7).sort((a, b) => b.id - a.id)
        //                    .reduce((carry, advance) => carry + advance.advance, 0)
        //            },
        //
        //            getDay (player) {
        //                return this.getAdvances(player).slice().sort((a, b) => b.id - a.id)[0].advance
        //            },
        //
        //            isLoose (experience) {
        //                return experience >= 0 ? false : true
        //            }
    }
}
</script>