<template>
    <page-load id="highscores">
        <page-title>
            <div class="pull-right" :show-timeout="100">
                <el-select
                        v-model="selectedWorld"
                        placeholder="All Worlds"
                        filterable
                        clearable
                        @change="selectWorld">
                    <el-option v-for="world, index in worlds"
                               :value="world.name"
                               :label="world.name"
                               :key="index"/>
                </el-select>

                <el-dropdown>
                    <el-button type="primary">
                        Highscores <i class="mdi mdi-chevron-down margin-left-10"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <router-link :to="{ name: 'highscores.experience' }">
                            <el-dropdown-item>Top Exp</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'knight', world: world } }">
                            <el-dropdown-item>Top Knight</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'druid', world: world } }">
                            <el-dropdown-item>Top Druid</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'sorcerer', world: world } }">
                            <el-dropdown-item>Top Sorcerer</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.experience', params: { vocation: 'paladin', world: world } }">
                            <el-dropdown-item>Top Paladin</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.skills', params: { skill: 'magic', world: world } }">
                            <el-dropdown-item>Top Magic</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.skills', params: { skill: 'axe', world: world } }">
                            <el-dropdown-item>Top Axe</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.skills', params: { skill: 'club', world: world } }">
                            <el-dropdown-item>Top Club</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.skills', params: { skill: 'sword', world: world } }">
                            <el-dropdown-item>Top Sword</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.skills', params: { skill: 'distance', world: world } }">
                            <el-dropdown-item>Top Distance</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'highscores.skills', params: { skill: 'shielding', world: world } }">
                            <el-dropdown-item>Top Shielding</el-dropdown-item>
                        </router-link>
                    </el-dropdown-menu>
                </el-dropdown>

            </div>

            <img :src="image_path_by_name('item', 'Medal of Honour')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Highscores</h2>
                <span>Best Tibia Players</span>
            </div>
        </page-title>

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
                    return this.$router.push({ name: 'highscores.experience', params: { vocation: 'all', world } })
                }
            }
        },

        mounted () {
            services.getWorlds()
                .then(response => this.worlds = response.data)
        }
    }
</script>
