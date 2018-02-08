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
                <h2>{{ character.name }}</h2>
                <span>{{ character.vocation }}</span>
            </div>
        </page-title>

        <character :character="character" :experience="experience" :skills="skills" :loadingSkills="loadingSkills" />

        <div class="row margin-top-40">
            <div class="col-md-8">
                <experience :experience="experience" :loading="loadingExperience" v-if="experience.length" />
                <no-data title="Experience advances" :message="`Unforntunately we can't track the exp statistics of ${character.name}. We can only track players statistics from tibia.com highscores.`" v-else />
                <deaths :deaths="deaths" v-if="deaths.length" />
            </div>

            <div class="col-md-4">
                <character-details :character="character" />
                <achievements :character="character" />
                <loyalty :skills="skills" :loading="loadingSkills" />
                <exp-share :character="character" />
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
    import Loyalty from './Loyalty'
    import ExpShare from './ExpShare'
    import Experience from './Experience'
    import Deaths from './Deaths'
    import NoData from './NoData'
    import services from '../services'

    export default {
        components: { CharacterDetails, Character, Achievements, Loyalty, ExpShare, Experience, Deaths, NoData },

        data () {
            return {
                loading: true,
                loadingSkills: true,
                loadingExperience: true,
                player: {},
                skills: [],
                experience: [],
                search: '',
            }
        },

        computed: {
            character () {
                return this.player
                    ? this.player
                    : {}
            },

            deaths () {
                return this.player && this.player.deaths
                    ? this.player.deaths
                    : []
            },

            thumb () {
                if (! this.player)
                    return 'Mage Outfits'

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
                }
            }
        },

        watch: {
            '$route.params.name' () {
                this.skills = []
                this.experience = []
                this.loadingSkills = true
                this.loadingExperience = true
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

                        this.loadSkills()
                        this.loadExperience()
                    })
                    .catch(() => this.player = false)
            },

            loadSkills () {
                this.loadingSkills = true
                services.getPlayerSkills(this.player.id)
                    .then(response => {
                        this.skills = response.data
                        this.loadingSkills = false
                    })
                    .catch(() => this.loadingSkills = false)
            },

            loadExperience () {
                services.getPlayerExperience(this.player.id)
                    .then(response => {
                        const advances = response.data

                        this.experience = advances.map((experience, index) => {
                            const advance = index ? parseFloat(experience.experience - advances[index - 1].experience) : 0
                            return { ...experience, advance }
                        })

                        this.loadingExperience = false
                    })
                    .catch(() => this.loadingExperience = false)
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