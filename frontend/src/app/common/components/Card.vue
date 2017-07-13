<template>
    <div class="card" :class="{ 'dark': dark }">
        <div class="icon">
            <slot name="icon"></slot>
        </div>

        <div class="information">
            <h3>{{ title }}</h3>
            <h5>{{ subtitle }}</h5>
        </div>

        <div class="anchor">
            <slot name="anchor"></slot>
        </div>

        <div class="clearfix"></div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            title: {
                type: String,
                required: true
            },

            subtitle: {
                type: String,
                required: true
            },

            dark: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
