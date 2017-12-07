<template>
    <el-dialog class="blacklist-dialog" title="Blacklist Loader" :visible.sync="visible">
        <textarea class="form-control" v-model="data"></textarea>

        <button class="btn btn-success margin-top-20" @click.prevent="load">
            Load
        </button>
    </el-dialog>
</template>

<script>
    export default {
        data () {
            return {
                visible: false,
                data: ''
            }
        },

        methods: {
            load () {
                try {
                    let data = JSON.parse(this.data)

                    if (data.blacklistTypes && data.listType == 'blacklist' && data.whitelistTypes) {
                        this.$emit('loaded', data)
                        this.visible = false
                        this.$message.success('Blacklist Loaded')
                    } else {
                        this.$message.error('Blacklist invalid')
                    }
                } catch (e) {
                    this.$message.error('Invalid blacklist')
                }
            }
        },

        mounted () {
            this.$root.$on('load::blacklist', () => this.visible = true)
        },

        beforeDestroy () {
            this.$root.$off('load::blacklist')
        }
    }
</script>