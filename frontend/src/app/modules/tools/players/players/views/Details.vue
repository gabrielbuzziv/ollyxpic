<template>
    <div>
        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-earth icon"></i>
                <div class="data">
                    <b>World</b>
                    <span>{{ world }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-city icon"></i>
                <div class="data">
                    <b>Residence</b>
                    <span>{{ player.residence }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details" v-if="player.house != null">
            <div class="detail">
                <i class="mdi mdi-home icon"></i>
                <div class="data">
                    <b>House</b>
                    <span>{{ player.house }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-gender-male-female icon"></i>
                <div class="data">
                    <b>Gender</b>
                    <span>{{ player.gender | capitalize }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details" v-if="player.married_to != null">
            <div class="detail">
                <i class="mdi mdi-ring icon"></i>
                <div class="data">
                    <b>Married to</b>
                    <span>
                        <router-link :to="{ name: 'tools.players', params: { name: player.married_to } }">
                            {{ player.married_to }}
                        </router-link>
                    </span>
                </div>
            </div>
        </panel>

        <panel class="character-details" v-if="player.guild != null">
            <div class="detail">
                <i class="mdi mdi-account-multiple icon"></i>
                <div class="data">
                    <b>Guild</b>
                    <span>
                        <a :href="`https://secure.tibia.com/community/?subtopic=guilds&page=view&GuildName=${player.guild}`"
                           target="_blank">
                            {{ player.guild }}
                        </a>
                    </span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-infinity icon"></i>
                <div class="data">
                    <b>Account Status</b>
                    <span v-if="player.premium">Premium Account</span>
                    <span v-else>Free Account</span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-login icon"></i>
                <div class="data">
                    <b>Last Login</b>
                    <span>{{ player.last_login | dateForHuman }}</span>
                </div>
            </div>
        </panel>
    </div>
</template>

<script>
export default {
    computed: {
        player () {
            return this.$store.getters['player/GET_PLAYER']
        },

        world() {
            return this.player && this.player.world
                ? this.player.world.name
                : ""
        }
    },

    filters: {
        dateForHuman(date) {
            return moment
                .tz(date, "YYYY-MM-DD HH:mm:ss", "Europe/Berlin")
                .fromNow()
        }
    }
}
</script>