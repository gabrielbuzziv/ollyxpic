<template>
    <input type="text" class="form-control" :value="data" @focus="onFocus" ref="shareInput">
</template>

<script type="text/babel">
    export default {
        props: ['data'],

        methods: {
            onFocus () {
                this.$refs.shareInput.select()
            }
        }
    }
</script>
