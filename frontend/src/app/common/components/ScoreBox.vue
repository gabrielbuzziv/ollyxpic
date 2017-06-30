<template>
    <panel class="scorebox">
        <div class="icon">
            <i class="mdi" :class="`mdi-${icon}`" :style="iconStyle"></i>
        </div>

        <div class="info">
            <strong class="name">{{ name }}</strong>
            <span class="score">{{ score ? score : 0 }}</span>
        </div>

        <div class="option">
            <slot name="option"></slot>
        </div>
    </panel>
</template>

<script type="text/babel">
    import { isEmpty } from 'lodash'

    export default {
        props: {
            icon: String,
            iconColor: String,
            name: String,
            score: [Number, String]
        },

        computed: {
            iconStyle () {
                return `color: ${this.iconColor}`
            }
        }
    }
</script>
