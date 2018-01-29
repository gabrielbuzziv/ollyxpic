<template>
    <panel class="achievements">
        <h4>Achievements</h4>

        <div class="rate">
            <div class="data">
                <b>{{ character.achievement_points }}</b>
                <span>points</span>
            </div>

            <el-progress type="circle" :percentage="completed"></el-progress>
        </div>

        <ul v-if="achievements.length">
            <li v-for="achievement in achievements">
                <b>{{ achievement.name }}</b>
                <el-rate v-model="achievement.stars" :max="3" disabled/>
            </li>
        </ul>
    </panel>
</template>

<script>
    export default {
        props: ['character', 'achievements'],

        computed: {
            completed () {
                return this.character && this.character.achievement_points
                    ? parseInt(this.character.achievement_points * 100 / 1080)
                    : 0
            }
        }
    }
</script>