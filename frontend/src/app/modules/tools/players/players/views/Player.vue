<template>
    <page-load id="players" v-if="player">
        <page-title>
            <div class="pull-right">
                <form class="search" @submit.prevent="searchPlayer">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Player name" v-model="search">
                        <button>
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </form>
            </div>

            <img :src="`/src/assets/images/${thumb}`" class="margin-right-15 big">
            <div class="title">
                <h2>{{ player.name || playerName }}</h2>
                <span>{{ player.vocation || 'Vocation' }}</span>
            </div>

            <router-link :to="{ name: 'compare.players', params: { first: player.name } }"
                         class="btn btn-default btn-rounded btn-compare"
                         v-if="player">
                <i class="mdi mdi-twitter-retweet margin-right-5"></i>
                Add to Compare
            </router-link>
        </page-title>

        <character />

        <div class="alert alert-warning">
            <p>
                All data is tracked from <a :href="highscoreUrl" target="_blank">tibia.com</a>,
                sometimes the leaderboards page from tibia take time to update making the information a little bit inconsistent.
            </p>
        </div>

        <div class="row margin-top-40">
            <div class="col-md-8">
                <page-load class="no-padding" :loading="loading">
                    <el-tabs class="main-tab" type="card" v-model="tabs">
                        <el-tab-pane label="Experience" name="experience">
                            <experience />
                        </el-tab-pane>

                        <el-tab-pane label="Deaths" name="death">
                            <deaths />
                        </el-tab-pane>

                        <el-tab-pane label="Social Network" name="Network" v-if="! network">
                            <social-tab />
                        </el-tab-pane>
                    </el-tabs>
                </page-load>
            </div>

            <div class="col-md-4 sidemenu">
                <social :network.sync="network" />
                <character-details />
                <achievements />
                <loyalty />
                <exp-share />
            </div>
        </div>
    </page-load>

    <page-load id="players" v-else>
        <page-title>
            <div class="pull-right">
                <form class="search" @submit.prevent="searchPlayer">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Player name" v-model="search">
                        <button>
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </form>
            </div>

            <img src="src/assets/images/sorcerer.svg" alt="" class="margin-right-15 big">
            <div class="title">
                <h2>Player</h2>
                <span>Details & Statistics</span>
            </div>
        </page-title>

        <div class="alert alert-warning not-found">
            <h4>Player not found</h4>
            <p>We're sorry, but we can't find <b>{{ $route.params.name }}</b>.</p>
        </div>
    </page-load>
</template>

<script>
    import CharacterDetails from './Details'
    import Character from './Character'
    import Achievements from './Achievements'
    import Loyalty from './Loyalty'
    import ExpShare from './ExpShare'
    import Experience from './Experience'
    import Advances from './Advances'
    import Social from './Social'
    import SocialTab from './SocialTab'
    import Deaths from './Deaths'
    import services from '../services'

    export default {
        components: { CharacterDetails, Character, Achievements, Loyalty, ExpShare, Experience, Advances, Deaths, Social, SocialTab },

        data () {
            return {
                loading: true,
                search: '',
                network: false,
                tabs: 'experience'
            }
        },

        computed: {
            playerName () {
                return this.$route.params.name
            },

            highscoreUrl () {
                const world = this.player && this.player.world ? this.player.world.name : ''
                let vocation = 0

                switch (this.player.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        vocation = 1
                        break
                    case 'Paladin':
                    case 'Royal Paladin':
                        vocation = 2
                        break
                    case 'Sorcerer':
                    case 'Master Sorcerer':
                        vocation = 3
                        break
                    case 'Druid':
                    case 'Elder Druid':
                        vocation = 4
                        break
                }

                return `https://secure.tibia.com/community/?subtopic=highscores&world=${world}&profession=${vocation}`
            },

            player () {
                return this.$store.getters['player/GET_PLAYER']
            },

            thumb () {
                if (! this.player)
                    return 'knight.svg'

                switch (this.player.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return 'knight.svg'
                    case 'Druid':
                    case 'Elder Druid':
                        return 'druid.svg'
                    case 'Sorcerer':
                    case 'Master Sorcerer':
                        return 'sorcerer.svg'
                    case 'Paladin':
                    case 'Royal Paladin':
                        return 'paladin.svg'
                    default:
                        return 'sorcerer.svg'
                }
            }
        },

        watch: {
            '$route.params.name' () {
                this.loading = true
                this.tabs = 'experience'
                this.load()
            }
        },

        methods: {
            load () {
                this.$store.dispatch('player/FETCH_PLAYER', { name: this.playerName })
                    .then(response => this.loading = false)
                    .catch(() => this.loading = false)
            },

            searchPlayer () {
                const player = this.search

                if (player == null || player == '')
                    return null

                this.$router.push({ name: 'players', params: { name: player } })
            }
        },

        mounted () {
            this.load()
        }
    }
</script>