<template>
    <panel class="share">
        <h4>Exp Share</h4>

        <div class="min">
            <b>{{ min | floor }}</b>
            <span>Min Level</span>
        </div>

        <div class="max">
            <b>{{ max | floor }}</b>
            <span>Max Level</span>
        </div>
    </panel>
</template>

<script>
    export default {

        computed: {
            player () {
                return this.$store.getters['player/GET_PLAYER']
            },

            min () {
                return this.player ? parseInt(this.player.level) / 3 * 2 : null;
            },

            max () {
                return this.player ? parseInt(this.player.level) * 3 / 2 : null;
            },
        },

        filters: {
            floor (data) {
                return data != null || data != '' ? Math.floor(data) : data
            }
        }
    }
</script>