<template>
    <panel class="deaths">
        <div class="deaths" v-if="deaths.length">
            <el-tabs v-model="deathTabs">
                <el-tab-pane :label="`All (${deaths.length})`" name="all">
                    <death :death="death" :key="index" v-for="death, index in deaths" />
                </el-tab-pane>

                <el-tab-pane pane :label="`PvP (${pvp.length})`" name="pvp" v-if="pvp.length">
                    <death :death="death" :key="index" v-for="death, index in pvp" />
                </el-tab-pane>

                <el-tab-pane pane :label="`PvE (${pve.length})`" name="pve" v-if="pve.length">
                    <death :death="death" :key="index" v-for="death, index in pve" />
                </el-tab-pane>

                <el-tab-pane :label="`Arena (${arena.length})`" name="arena" v-if="arena.length">
                    <death :death="death" :key="index" v-for="death, index in arena" />
                </el-tab-pane>
            </el-tabs>
        </div>

        <div class="alert alert-info margin-bottom-0" v-else>
            Sorry, we don't find deaths for this character.
        </div>
    </panel>
</template>

<script>
    import Death from './Death'

    export default {
        components: { Death },

        data () {
            return {
                deathTabs: 'all',
            }
        },

        computed: {
            player () {
                return this.$store.getters['player/GET_PLAYER']
            },

            deaths () {
                return this.player && this.player.deaths ? this.player.deaths : []
            },

            arena () {
                return this.deaths.filter(death => death.reason.indexOf('by energy') !== - 1
                    || death.reason.indexOf('by earth') !== - 1
                    || death.reason.indexOf('by death') !== - 1
                    || death.reason.indexOf('by Pi') !== - 1)
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