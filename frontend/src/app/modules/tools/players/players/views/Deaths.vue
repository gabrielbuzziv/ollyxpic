<template>
    <panel class="deaths">
        <h4>Recent Deaths</h4>

        <table class="table">
            <tbody>
                <el-tabs v-model="tabs">
                    <el-tab-pane label="All Deaths" name="all">
                        <death :death="death" :key="index" v-for="death, index in deaths" />
                    </el-tab-pane>

                    <el-tab-pane label="PvP Deaths" name="pvp" v-if="pvp.length">
                        <death :death="death" :key="index" v-for="death, index in pvp" />
                    </el-tab-pane>

                    <el-tab-pane label="PvE Deaths" name="pve" v-if="pve.length">
                        <death :death="death" :key="index" v-for="death, index in pve" />
                    </el-tab-pane>
                </el-tabs>
            </tbody>
        </table>
    </panel>
</template>

<script>
    import Death from './Death'

    export default {
        props: ['deaths'],

        components: { Death },

        data () {
            return {
                tabs: 'all'
            }
        },

        computed: {
            pvp () {
                return this.deaths.filter(death => death.reason.indexOf('Killed by') !== -1 || death.reason.indexOf('Slain by') !== -1 || death.reason.indexOf('Annihilated by') !== -1)
            },

            pve () {
                return this.deaths.filter(death => death.reason.indexOf('Died by a') !== -1)
            }
        }
    }
</script>