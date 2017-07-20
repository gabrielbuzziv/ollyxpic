<template>
    <li :class="{ 'active': isActive }">
        <a href="#" @click.prevent="onClick">
            <slot></slot>
        </a>
    </li>
</template>

<script type="text/babel">
    export default {
        props: {
            tab: {
                type: String,
                required: true
            },

            active: {
                type: Boolean,
                default: false
            }
        },

        data () {
            return {
                isActive: false
            }
        },

        computed: {
            href () {
                return `#${this.tab}`
            },
        },

        watch: {
            active () {
                this.isActive = this.active
            }
        },

        methods: {
            onClick () {
                this.$root.$emit('show::tab', this.tab)
                this.$emit('click')
            },

            activate () {
                this.isActive = true
            },

            disactivate () {
                this.isActive = false
            }
        },

        mounted () {
            this.isActive = this.active

            this.$root.$on('show::tab', tab => tab == this.tab ? this.activate() : this.disactivate())
        },

        beforeDestroy () {
            this.$root.$off('show::tab')
        }
    }
</script>
