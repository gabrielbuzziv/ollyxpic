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
                    return this.$router.push({ name: 'highscores.experience', params: { vocation: 'all', world } })
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
