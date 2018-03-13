<template>
    <panel class="rating">
        <h4>Achievements</h4>

        <div class="rate">
            <div class="data">
                <b>{{ player.achievement_points }}</b>
                <span>points</span>
            </div>

            <el-progress type="circle" :percentage="completed"></el-progress>
        </div>
    </panel>
</template>

<script>
    export default {
        computed: {
            player () {
                return this.$store.getters['player/GET_PLAYER'].details
            },

            completed () {
                return this.player && this.player.achievement_points
                    ? parseInt(this.player.achievement_points * 100 / 1077)
                    : 0
            }
        }
    }
</script>