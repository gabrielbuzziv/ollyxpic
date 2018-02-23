<template>
    <div class="row">
        <div class="col-md-6">
            <panel class="card-panel" :class="{ 'danger': isNegative(monthExperience) }">
                <div class="icon"><i class="mdi" :class="[isNegative(monthExperience) ? 'mdi-trending-down' : 'mdi-trending-up']"></i></div>
                <div class="data">
                    <b>Month Experience</b>
                    <span>{{ monthExperience.format() }}</span>
                </div>
                <month-experience-chart :experience="experience" :month="month"/>
            </panel>
        </div>

        <div class="col-md-6">
            <panel class="card-panel light" :class="{ 'danger': isNegative(monthLevels) }">
                <div class="icon"><i class="mdi" :class="[isNegative(monthLevels) ? 'mdi-trending-down' : 'mdi-trending-up']"></i></div>
                <div class="data">
                    <b>Levels UP</b>
                    <span>{{ monthLevels }}</span>
                </div>
                <month-level-chart :experience="experience" :month="month"/>
            </panel>
        </div>
    </div>
</template>

<script>
    import MonthExperienceChart from './MonthExperienceChart'
    import MonthLevelChart from './MonthLevelChart'

    export default {
        props: ['experience', 'month'],

        components: { MonthExperienceChart, MonthLevelChart },

        computed: {
            monthExperience () {
                const firstOfMonth = this.experience[this.experience.length - 1]
                const lastOfMonth = this.experience[0]
                return this.experience.length ? lastOfMonth.experience - firstOfMonth.experience : 0
            },

            monthLevels () {
                const firstOfMonth = this.experience[this.experience.length - 1]
                const lastOfMonth = this.experience[0]
                return this.experience.length ? lastOfMonth.level - firstOfMonth.level : 0
            }
        },

        methods: {
            isNegative (value) {
                return value < 0 ? true : false
            }
        }
    }
</script>