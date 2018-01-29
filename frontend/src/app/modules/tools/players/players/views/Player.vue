<template>
    <page-load id="players" :loading="loading">
        <page-title>
            <div class="pull-right">

            </div>

            <img :src="outfit(outfiter, character.sex)" alt="" class="margin-right-15">
            <div class="title">
                <h2>{{ character.name }}</h2>
                <span>{{ character.vocation }}</span>
            </div>
        </page-title>


        <character :character="character" :experience="experience" />

        <div class="row">
            <div class="col-md-8">
                <character-details :character="character" :achievements="achievements" />
            </div>

            <div class="col-md-4">
                <achievements :character="character" :achievements="achievements" />
            </div>
        </div>

        <experience :experience="experience" v-if="experience.length" />
        <deaths :deaths="deaths" v-if="deaths.length" />
    </page-load>
</template>

<script>
    import CharacterDetails from './Details'
    import Character from './Character'
    import Achievements from './Achievements'
    import Experience from './Experience'
    import Deaths from './Deaths'
    import services from '../services'

    export default {
        components: { CharacterDetails, Character, Achievements, Experience, Deaths },

        data () {
            return {
                loading: true,
                player: {}
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
                    .catch(() => this.loading = false)
            }
        },

        mounted () {
            this.load()
        }
    }
</script>