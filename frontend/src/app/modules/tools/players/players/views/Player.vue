<template>
    <page-load id="players" :loading="loading" v-if="player">
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

            <img :src="outfit(outfiter, character.sex)" alt="" class="margin-right-15">
            <div class="title">
                <h2>{{ character.name }}</h2>
                <span>{{ character.vocation }}</span>
            </div>
        </page-title>

        <character :character="character" :experience="experience" />

        <div class="row margin-top-40">
            <div class="col-md-8">
                <experience :experience="experience" v-if="experience.length" />
                <no-data title="Experience advances" :message="`Unforntunately we can't track the exp statistics of ${character.name}. We can only track players statistics from tibia.com highscores.`" v-else />
                <deaths :deaths="deaths" v-if="deaths.length" />
            </div>

            <div class="col-md-4">
                <character-details :character="character" :achievements="achievements" />
                <achievements :character="character" :achievements="achievements" />
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

            <img :src="outfit('Mage Outfits')" alt="" class="margin-right-15">
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
    import Experience from './Experience'
    import Deaths from './Deaths'
    import NoData from './NoData'
    import services from '../services'

    export default {
        components: { CharacterDetails, Character, Achievements, Experience, Deaths, NoData },

        data () {
            return {
                loading: true,
                player: {},
                search: ''
            }
        },

        computed: {
            character () {
                return this.player && this.player.details && this.player.details.data
                    ? this.player.details.data
                    : {}
            },

            achievements () {
                return this.player && this.player.details && this.player.details.achievements
                    ? this.player.details.achievements
                    : []
            },

            deaths () {
                return this.player && this.player.details && this.player.details.deaths
                    ? this.player.details.deaths
                    : []
            },

            experience () {
                return this.player && this.player.experience
                    ? this.player.experience
                    : []
            },

            outfiter () {
                if (! this.player || ! this.player.details || ! this.player.details.data)
                    return 'Mage Outfits'

                switch (this.player.details.data.vocation) {
                    case 'Knight':
                    case 'Elite Knight':
                        return 'Warrior Outfits'
                    case 'Druid':
                    case 'Elder Druid':
                        return 'Summoner Outfits'
                    case 'Sorcerer':
                    case 'Master Sorcerer':
                        return 'Mage Outfits'
                    case 'Paladin':
                    case 'Royal Paladin':
                        return 'Hunter Outfits'
                }
            }
        },

        watch: {
            '$route.params.name' () {
                this.load()
            }
        },

        methods: {
            load () {
                this.loading = true
                services.getPlayer(this.$route.params.name)
                    .then(response => {
                        this.player = response.data
                        this.loading = false
                    })
                    .catch(() => this.player = false)
            },

            searchPlayer () {
                const player = this.search

                if (player == null || player == '')
                    return null

                this.$router.push({ name: 'tools.players', params: { name: player } })
            }
        },

        mounted () {
            this.load()
        }
    }
</script>