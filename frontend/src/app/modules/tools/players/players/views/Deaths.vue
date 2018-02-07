<template>
    <panel class="deaths">
        <h4>Recent Deaths</h4>

        <div class="deaths">
            <el-tabs v-model="tabs">
                <el-tab-pane :label="`All (${deaths.length})`" name="all">
                    <div class="death-collapse" :class="{ 'closed': ! allOpen }">
                        <death :death="death" :key="index" v-for="death, index in deaths" />
                    </div>

                    <div class="show-more" v-if="! allOpen" @click="allOpen = true">
                        <button class="btn btn-rounded">
                            <i class="mdi mdi-plus-circle margin-right-5"></i>
                            Load more
                        </button>
                    </div>
                </el-tab-pane>

                <el-tab-pane pane :label="`PvP (${pvp.length})`" name="pvp" v-if="pvp.length">
                    <div class="death-collapse" :class="{ 'closed': ! pvpOpen }">
                        <death :death="death" :key="index" v-for="death, index in pvp" />
                    </div>

                    <div class="show-more" v-if="! pvpOpen" @click="pvpOpen = true">
                        <button class="btn btn-rounded">
                            <i class="mdi mdi-plus-circle margin-right-5"></i>
                            Load more
                        </button>
                    </div>
                </el-tab-pane>

                <el-tab-pane pane :label="`PvE (${pve.length})`" name="pve" v-if="pve.length">
                    <div class="death-collapse" :class="{ 'closed': ! pveOpen }">
                        <death :death="death" :key="index" v-for="death, index in pve" />
                    </div>

                    <div class="show-more" v-if="! pveOpen" @click="pveOpen = true">
                        <button class="btn btn-rounded">
                            <i class="mdi mdi-plus-circle margin-right-5"></i>
                            Load more
                        </button>
                    </div>
                </el-tab-pane>

                <el-tab-pane :label="`Arena (${arena.length})`" name="arena" v-if="arena.length">
                    <div class="death-collapse" :class="{ 'closed': ! arenaOpen }">
                        <death :death="death" :key="index" v-for="death, index in arena" />
                    </div>

                    <div class="show-more" v-if="! arenaOpen" @click="arenaOpen = true">
                        <button class="btn btn-rounded">
                            <i class="mdi mdi-plus-circle margin-right-5"></i>
                            Load more
                        </button>
                    </div>
                </el-tab-pane>
            </el-tabs>
        </div>
    </panel>
</template>

<script>
    import Death from './Death'

    export default {
        props: ['deaths'],

        components: { Death },

        data () {
            return {
                tabs: 'all',
                allOpen: false,
                pvpOpen: false,
                pveOpen: false,
                arenaOpen: false,
            }
        },

        computed: {
            arena () {
                return this.deaths.filter(death => death.reason.indexOf('by energy') !== - 1 || death.reason.indexOf('by earth') !== - 1 || death.reason.indexOf('by death') !== - 1)
            },

            pvp () {
                return this.deaths.filter(death => death.involved.length)
            },

            pve () {
                return this.deaths.filter(death => death.reason.indexOf('Died by a') !== - 1)
            }
        },

        mounted () {
            this.allOpen = this.deaths.length > 4 ? false : true
            this.pvpOpen = this.pvp.length > 4 ? false : true
            this.pveOpen = this.pve.length > 4 ? false : true
            this.arenaOpen = this.arena.length > 4 ? false : true
        }
    }
</script>