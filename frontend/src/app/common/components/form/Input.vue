<template>
    <input type="text" :name="name" :value="data" @input="update" class="form-control" @click="onClick">
</template>

<script type="text/babel">
    export default {
        props: ['name', 'data'],

        methods: {
            update (event) {
                this.$emit('input', event.target.value)
            },

            onClick (e) {
                this.$emit('click', e)
            }
        }
    }
</script>
