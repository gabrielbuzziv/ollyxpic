<template>
    <div id="content">
        <div class="container" :class="{ 'margin-top-40': isLogged }">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default  {
        computed: {
            isLogged () {
                return this.$store.getters['global/IS_LOGGED']
            }
        }
    }
</script>