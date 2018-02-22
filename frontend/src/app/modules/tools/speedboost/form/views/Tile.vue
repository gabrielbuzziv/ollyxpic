<template>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <panel class="tile">
            <div class="thumb">
                <img :src="tile_path(tile.object_id)" />
            </div>

            <div class="info">
                <span class="name">{{ tile.name }}</span>
                <small class="friction">
                    <b>Friction:</b>
                    <span>{{ tile.friction }}</span>
                </small>
            </div>

            <div class="speeds">
                <div class="speed">
                    <b>Boost:</b>
                    <span>{{ speed.boost }}</span>

                    <i class="mdi mdi-check text-success" v-if="speed.boost <= player.speed"></i>
                </div>

                <div class="speed">
                    <b>GM Speed:</b>
                    <span>{{ speed.gamemaster }}</span>

                    <i class="mdi mdi-check text-success" v-if="speed.gamemaster <= player.speed"></i>
                </div>
            </div>
        </panel>
    </div>
</template>

<script>
    import Speeds from './Speeds'

    export default {
        props: ['tile', 'player'],

        computed: {
            speed () {
                return Speeds[this.tile.friction]
            }
        }
    }
</script>