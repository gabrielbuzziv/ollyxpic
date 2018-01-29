<template>
    <div>
        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-earth icon"></i>
                <div class="data">
                    <b>World</b>
                    <span>{{ character.world }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-city icon"></i>
                <div class="data">
                    <b>Residence</b>
                    <span>{{ character.residence }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details" v-if="character.house">
            <div class="detail">
                <i class="mdi mdi-home icon"></i>
                <div class="data">
                    <b>House</b>
                    <span>{{ character.house.town }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-gender-male-female icon"></i>
                <div class="data">
                    <b>Gender</b>
                    <span>{{ character.sex | capitalize }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details" v-if="character.married_to">
            <div class="detail">
                <i class="mdi mdi-ring icon"></i>
                <div class="data">
                    <b>Married to</b>
                    <span>
                            <router-link :to="{ name: 'tools.players', params: { name: character.married_to } }">
                                {{ character.married_to }}
                            </router-link>
                        </span>
                </div>
            </div>
        </panel>

        <panel class="character-details" v-if="character.guild">
            <div class="detail">
                <i class="mdi mdi-account-multiple icon"></i>
                <div class="data">
                    <b>Guild</b>
                    <span>
                            <a :href="`https://secure.tibia.com/community/?subtopic=guilds&page=view&GuildName=${character.guild.name}`"
                               target="_blank">
                                {{ character.guild.name }}
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
                    <span>{{ character.account_status }}</span>
                </div>
            </div>
        </panel>

        <panel class="character-details">
            <div class="detail">
                <i class="mdi mdi-login icon"></i>
                <div class="data">
                    <b>Last Login</b>
                    <span>{{ character.last_login[0].date | dateForHuman }}</span>
                </div>
            </div>
        </panel>
    </div>
</template>

<script>
    export default {
        props: ['character', 'achievements'],

        filters: {
            dateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'Europe/Berlin').fromNow()
            }
        }
    }
</script>