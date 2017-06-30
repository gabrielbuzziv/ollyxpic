<template>
    <textarea :name="name" @input="update" class="form-control">{{ data }}</textarea>
</template>

<script type="text/babel">
    export default {
        props: ['name', 'data'],

        methods: {
            update (event) {
                this.$emit('input', event.target.value)
            }
        }
    }
</script>
