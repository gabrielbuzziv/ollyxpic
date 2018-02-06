<template>
    <div>
        <transition name="pageLoad">
            <div class="page" :class="pageClass" v-if="loaded && ! loading">
                <slot></slot>
            </div>
        </transition>

        <div class="page-loading margin-bottom-40" v-if="loading">
            <slot name="loading">
                <div class="loader inline"></div>
                <span>Loading</span>
            </slot>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            pageClass: String,
            loading: {
                type: Boolean,
                default: false
            }
        },

        data () {
            return {
                loaded: false
            }
        },

        watch: {
            loading () {
                if (! this.loading) {
                    this.loaded = true
                }
            }
        },

        mounted () {
            if (! this.loading) {
                this.loaded = true
            }
        }
    }
</script>
