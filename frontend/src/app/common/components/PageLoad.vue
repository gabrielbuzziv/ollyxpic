<template>
    <transition name="page">
        <div class="page" v-show="loaded">
            <slot></slot>
        </div>
    </transition>
</template>

<script type="text/babel">
    export default {
        data () {
            return {
                loaded: false
            }
        },

        mounted () {
            this.loaded = true
        }
    }
</script>
