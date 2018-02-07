<template>
    <panel class="achievements">
        <h4>Achievements</h4>

        <div class="rate">
            <div class="data">
                <b>{{ character.achievements }}</b>
                <span>points</span>
            </div>

            <el-progress type="circle" :percentage="completed"></el-progress>
        </div>
    </panel>
</template>

<script>
    export default {
        props: ['character'],

        computed: {
            completed () {
                return this.character && this.character.achievements
                    ? parseInt(this.character.achievements * 100 / 1077)
                    : 0
            }
        }
    }
</script>