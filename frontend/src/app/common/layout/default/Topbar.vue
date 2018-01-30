<template>
    <div id="topbar">
        <div class="container">
            <div class="pull-left">
                <span class="rashid">
                    <img src="/src/assets/images/rashid.gif" alt="">
                    <span>
                        <span class="welcome">Hello, my name is Rashid</span>
                        <span class="visit">come visit me in <b>{{ rashid }}</b></span>
                    </span>
                </span>
            </div>

            <div class="pull-right">
                <!--<div class="search-player">-->
                    <!--<input type="text" placeholder="Search character ..." @keypress.enter="searchPlayer" v-model="character">-->
                    <!--<button @click.prevent="searchPlayer">-->
                        <!--<i class="mdi mdi-magnify"></i>-->
                    <!--</button>-->
                <!--</div>-->


                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="inline  margin-right-5">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="J8VPUH8PS9ANS">
                    <button name="submit" class="btn btn-default" target="_blank">
                        <i class="mdi mdi-coffee margin-right-5"></i>
                        Pay us a coffee
                    </button>
                </form>

                <button class="btn btn-default" @click.prevent="$root.$emit('show::login')" v-if="! isLogged">
                    <i class="mdi mdi-login margin-right-5"></i>
                </button>

                <span class="margin-left-15 user-stats" v-else>
                    {{ user.name }}
                    <span class="margin-right-5 margin-left-5">|</span>
                    <a href="#" @click.prevent="logout">Logout</a>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                character: ''
            }
        },

        computed: {
            rashid () {
                const cities = ['Carlin', 'Svargrond', 'Libert Bay', 'Port Hope', 'Ankrahmun', 'Darashia', 'Edron']
                const today = moment(new Date()).day()

                return cities[today]
            },

            user () {
                return this.$store.getters['global/GET_USER']
            },

            isLogged () {
                return this.$store.getters['global/IS_LOGGED']
            },

            logout () {
                this.$store.commit('global/TOKEN', '')
                this.$store.commit('global/USER', '')

                localStorage.removeItem('auth_token')
                localStorage.removeItem('auth_user')
            }
        },

        methods: {
            searchPlayer () {
                if (this.character == '' || this.character == null) {
                    return false
                }

                this.$router.push({ name: 'tools.players', params: { name: this.character } })
            },
        }
    }
</script>