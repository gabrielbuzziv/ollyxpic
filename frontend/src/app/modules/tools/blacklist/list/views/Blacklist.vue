<template>
    <el-dialog class="blacklist-dialog" title="Blacklist" :visible.sync="visible">
        <button class="btn btn-sm margin-bottom-15" @click.prevent="copy">
            <i class="mdi mdi-clipboard margin-right-5"></i>
            Copy to Clipboard
        </button>
        <textarea readonly ref="blacklist" class="form-control">{{ blacklist }}</textarea>
    </el-dialog>
</template>

<script>
    export default {
        props: ['blacklist'],

        data () {
            return {
                visible: false
            }
        },

        methods: {
            copy () {
                this.$refs.blacklist.select()
                document.execCommand('Copy')
            }
        },

        mounted () {
            this.$root.$on('generate::blacklist', () => this.visible = true)
        },

        beforeDestroy () {
            this.$root.$off('generate::blacklist')
        }
    }
</script>