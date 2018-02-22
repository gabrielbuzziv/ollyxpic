<template>
    <panel class="advances">
        <pre>{{ player.id }}</pre>

        <el-tabs v-model="tabs" @tab-click="loadExperience">
            <el-tab-pane :label="month.label" :name="month.value" :key="index" v-for="month, index in months">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Level</th>
                            <th>Experience</th>
                            <th>Advance</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="advance in experience">
                            <td>{{ advance.updated_at }}</td>
                            <td>{{ advance.level }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </el-tab-pane>
        </el-tabs>

        <!--<el-table class="advances"-->
        <!--:data="experience"-->
        <!--:default-sort="{ prop: 'updated_at', order: 'descending' }">-->
        <!--<el-table-column prop="updated_at" label="Date" sortable>-->
        <!--<template slot-scope="scope">-->
        <!--<b>{{ scope.row.updated_at | date }}</b>-->
        <!--<span>{{ scope.row.updated_at | dateForHuman }}</span>-->
        <!--</template>-->
        <!--</el-table-column>-->

        <!--<el-table-column prop="level" label="Level" class-name="level" sortable>-->
        <!--<template slot-scope="scope">-->
        <!--<span>Level</span>-->
        <!--<b>{{ scope.row.level }}</b>-->

        <!--<el-progress :percentage="getExperienceBar(scope.row).percentage"-->
        <!--:show-text="false"/>-->
        <!--</template>-->
        <!--</el-table-column>-->

        <!--<el-table-column prop="experience" label="Experience" sortable>-->
        <!--<template slot-scope="scope">-->
        <!--<b>{{ scope.row.experience.format() }}</b>-->
        <!--<span>Experience</span>-->
        <!--</template>-->
        <!--</el-table-column>-->

        <!--<el-table-column prop="advance" label="Advances" class-name="advance" sortable>-->
        <!--<template slot-scope="scope">-->
        <!--<div v-if="scope.row.advance >= 0">-->
        <!--<b>+{{ scope.row.advance.format() }}</b>-->
        <!--<span>+ Experience</span>-->
        <!--</div>-->

        <!--<div v-else>-->
        <!--<b class="loose">{{ scope.row.advance.format() }}</b>-->
        <!--<span>+ Experience</span>-->
        <!--</div>-->
        <!--</template>-->
        <!--</el-table-column>-->
        <!--</el-table>-->
    </panel>
</template>

<script>
    import services from '../services'

    export default {
        props: ['player'],

        data () {
            return {
                tabs: 0,
                months: [],
                experience: []
            }
        },

        methods: {
            load () {
                services.getMonths()
                    .then(response => {
                        this.months = response.data.map(month => ({
                            label: moment(month).format('MMM YYYY'),
                            date: month
                        }))

                        this.tab = this.months[0].value
                    })
            },

            loadExperience (month) {
                services.getPlayerExperience(this.player.id, this.months[month].value)
                    .then(response => {
                        const advances = response.data

                        this.experience = advances.map((experience, index) => {
                            const advance = index ? parseFloat(experience.experience - advances[index - 1].experience) : 0
                            return { ...experience, advance }
                        })

                        this.loadingExperience = false
                    })
            },

            getExperienceBar (advance) {
                const nextLevel = advance.level + 1
                const nextLevelExp = ((50 * Math.pow((nextLevel - 1), 3)) - (150 * Math.pow((nextLevel - 1), 2)) + (400 * (nextLevel - 1))) / 3
                const currentExp = advance.experience
                const expToNextLevel = 50 * Math.pow(advance.level, 2) - 150 * advance.level + 200
                const expLeft = nextLevelExp - currentExp
                const currentLeveledExp = expToNextLevel - expLeft
                const percentage = parseInt((currentLeveledExp * 100) / expToNextLevel)

                return this.experience
                    ? { leftExperience: expLeft, percentage: percentage }
                    : { leftExperience: 0, percentage: 0 }
            },
        },

        mounted () {
            this.$root.$on('player::loaded', () => this.load())
        },

        beforeDestroy () {
            this.$root.$off('player::loaded')
        }
    }
</script>
