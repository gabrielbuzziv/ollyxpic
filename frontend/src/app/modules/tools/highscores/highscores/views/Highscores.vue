<template>
    <page-load id="highscores">
        <page-title>
            <div class="pull-right filter" :show-timeout="100">
                <el-select
                        v-model="selectedWorld"
                        placeholder="All Worlds"
                        filterable
                        clearable
                        popper-class="highscores-world-popper"
                        @change="selectWorld">
                    <el-option v-for="world, index in worlds"
                               :value="world.name"
                               :label="world.name"
                               :key="index">
                        <b>{{ world.name }}</b>
                        <span>{{ world.type }}</span>
                    </el-option>
                </el-select>
            </div>

            <img :src="image_path_by_name('item', 'Medal of Honour')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Highscores</h2>
                <span>Best Tibia Players</span>
            </div>
        </page-title>

        <nav class="highscores-menu">
            <ul class="experience">
                <li class="all">
                    <router-link :to="{ name: 'highscores.experience', params: { vocation: null, world } }" exact>
                        <div class="thumb">
                            <img src="/src/assets/images/raster.png" alt="">
                        </div>
                        <span class="hidden-md hidden-lg">Global</span>
                    </router-link>
                </li>

                <div class="vocations">
                    <li>
                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'knight', world } }" exact>
                            <div class="thumb">
                                <img src="/src/assets/images/knight.svg" alt="">
                            </div>
                            <span>Top Knights</span>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'druid', world } }" exact>
                            <div class="thumb">
                                <img src="/src/assets/images/druid.svg" alt="">
                            </div>
                            <span>Top Druids</span>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'sorcerer', world } }" exact>
                            <div class="thumb">
                                <img src="/src/assets/images/sorcerer.svg" alt="">
                            </div>
                            <span>Top Sorcerers</span>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'paladin', world } }" exact>
                            <div class="thumb">
                                <img src="/src/assets/images/paladin.svg" alt="">
                            </div>
                            <span>Top Paladins</span>
                        </router-link>
                    </li>
                </div>
            </ul>

            <ul class="skills">
                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'magic', world } }" exact>
                        <span>Top Magic</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'axe', world } }" exact>
                        <span>Top Axe</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'club', world } }" exact>
                        <span>Top Club</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'sword', world } }" exact>
                        <span>Top Sword</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'distance', world } }" exact>
                        <span>Top Distance</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'shielding', world } }" exact>
                        <span>Top Shield</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'achievements', world } }" exact>
                        <span>Top Achievements</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.skills', params: { skill: 'loyalty', world } }" exact>
                        <span>Top Loyalty</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.experience.type', params: { type: 'pvp' } }" exact>
                        <span>Top Open PvP</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.experience.type', params: { type: 'npvp' } }" exact>
                        <span>Top Optional PvP</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.experience.type', params: { type: 'hardcore' } }" exact>
                        <span>Top Hardcore PvP</span>
                    </router-link>
                </li>

                <li>
                    <router-link :to="{ name: 'highscores.experience.type', params: { type: 'retro' } }" exact>
                        <span>Top Retro Open PvP</span>
                    </router-link>
                </li>
            </ul>
        </nav>

        <router-view></router-view>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                selectedWorld: '',
                worlds: []
            }
        },

        computed: {
            world () {
                return this.$route.params.world ? this.$route.params.world : null
            }
        },

        watch: {
            '$route.params.world' () {
                this.selectedWorld = this.$route.params.world
            }
        },

        methods: {
            selectWorld () {
                const world = this.selectedWorld ? this.selectedWorld : null
                if (this.$route.params.skill) {
                    const skill = this.$route.params.skill
                    return this.$router.push({ name: 'highscores.skills', params: { skill, world } })
                }

                if (this.$route.params.vocation) {
                    const vocation = this.$route.params.vocation
                    return this.$router.push({ name: 'highscores.experience', params: { vocation, world } })
                }

                if (this.$route.name == 'highscores.experience') {
                    return this.$router.push({ name: 'highscores.experience', params: { vocation: null, world } })
                }
            }
        },

        mounted () {
            services.getWorlds()
                .then(response => {
                    this.worlds = response.data
                    this.selectedWorld = this.$route.params.world
                })
        }
    }
</script>
