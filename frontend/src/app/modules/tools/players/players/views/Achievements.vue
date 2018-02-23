<template>
    <panel class="rating">
        <h4>Achievements</h4>

        <div class="rate">
            <div class="data">
                <b>{{ player.achievements }}</b>
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
                return this.$store.getters['player/GET_PLAYER']
            },

            completed () {
                return this.player && this.player.achievements
                    ? parseInt(this.player.achievements * 100 / 1077)
                    : 0
            }
        }
    }
</script>