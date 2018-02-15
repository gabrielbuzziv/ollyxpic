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
        props: ['character'],

        computed: {
            min () {
                return this.character ? parseInt(this.character.level) / 3 * 2 : null;
            },

            max () {
                return this.character ? parseInt(this.character.level) * 3 / 2 : null;
            },
        },

        filters: {
            floor (data) {
                return data != null || data != '' ? Math.floor(data) : data
            }
        }
    }
</script>