<template>
    <div class="dropdown">
        <a href="#" :class="buttonClass" @click.prevent="toggle" ref="dropdown">
            <slot name="button"></slot>
        </a>

        <transition name="fade">
            <ul class="dropdown-menu" :class="{ 'dropdown-menu-right': right }" v-show="open">
                <slot></slot>
            </ul>
        </transition>
    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            button: String,

            right: {
                type: Boolean,
                default: false
            }
        },

        data () {
            return {
                open: false
            }
        },

        computed: {
            buttonClass () {
                if (this.button) {
                    return this.button.split(' ')
                }

                return null
            }
        },

        methods: {
            toggle () {
                this.open = ! this.open
            }
        },

        mounted () {
            this.$refs.dropdown.addEventListener('blur', e => {
                setTimeout(() => {
                    this.open = false
                }, 200)
            })
        }
    }
</script>