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

        <div class="col-md-8">
            <character :character="character" />
            <experience :experience="experience" v-if="experience.length" />
        </div>

        <div class="col-md-4">
            <panel v-if="character.house">
                <h4>House</h4>

                <ul>
                    <li>
                        <b>{{ character.house.town }}</b>
                        <span>Town</span>
                    </li>

                    <li>
                        <b>{{ character.house.name }}</b>
                        <span>House name</span>
                    </li>
                </ul>
            </panel>

            <panel class="achievements">
                <h4>Achievements</h4>

                <div class="rate">
                    <b>{{ character.achievement_points }}</b>
                    <span>points</span>
                </div>

                <ul v-if="achievements.length">
                    <li v-for="achievement in achievements">
                        <b>{{ achievement.name }}</b>
                        <el-rate v-model="achievement.stars" :max="3" disabled />
                    </li>
                </ul>
            </panel>
        </div>
    </page-load>
</template>

<script>
    import Character from './Character'
    import Experience from './Experience'
    import services from '../services'

    export default {
        components: { Character, Experience },

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