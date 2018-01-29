<template>
    <panel class="character-details">
        <h4>Details</h4>

        <ul class="details">
            <li>
                <i class="mdi mdi-earth icon"></i>
                <div class="data">
                    <b>World</b>
                    <span>{{ character.world }}</span>
                </div>
            </li>

            <li>
                <i class="mdi mdi-city icon"></i>
                <div class="data">
                    <b>Residence</b>
                    <span>{{ character.residence }}</span>
                </div>
            </li>

            <li v-if="character.house">
                <i class="mdi mdi-home icon"></i>
                <div class="data">
                    <b>House</b>
                    <span>{{ character.house.town }}</span>
                </div>
            </li>

            <li>
                <i class="mdi mdi-gender-male-female icon"></i>
                <div class="data">
                    <b>Gender</b>
                    <span>{{ character.sex | capitalize }}</span>
                </div>
            </li>

            <li v-if="character.married_to">
                <i class="mdi mdi-ring icon"></i>
                <div class="data">
                    <b>Married to</b>
                    <span>
                        <router-link :to="{ name: 'tools.players', params: { name: character.married_to } }">
                            {{ character.married_to }}
                        </router-link>
                    </span>
                </div>
            </li>

            <li v-if="character.guild">
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
            </li>

            <li>
                <i class="mdi mdi-infinity icon"></i>
                <div class="data">
                    <b>Account Status</b>
                    <span>{{ character.account_status }}</span>
                </div>
            </li>

            <li>
                <i class="mdi mdi-login icon"></i>
                <div class="data">
                    <b>Last Login</b>
                    <span>{{ character.last_login[0].date | dateForHuman }}</span>
                </div>
            </li>
        </ul>
    </panel>
</template>

<script>
    export default {
        props: ['character'],

        data () {
            return {
                details: [
                    { icon: 'earth', label: 'Vocations', value: this.character.vocation },
                    { icon: 'earth', label: 'World', value: this.character.world },
                    { icon: 'home', label: 'Residence', value: this.character.residence },
                    { icon: 'gender-male-female', label: 'Sex', value: this.character.sex },
                    { icon: 'ring', label: 'Married To', value: this.character.married_to },
                    { icon: 'account-multiple', label: 'Guild', value: this.character.guild.name },
                    { icon: 'home', label: 'Account Status', value: this.character.account_status },
                    {
                        icon: 'login',
                        label: 'Last Login',
                        value: this.character.last_login[0].date,
                        filter: 'dateForHuman'
                    },
                ]
            }
        },

        filters: {
            dateForHuman (date) {
                console.log(date)
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'Europe/Berlin').fromNow()
            }
        }
    }
</script>