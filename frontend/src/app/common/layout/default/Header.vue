<template>
    <div id="header">
        <div class="container">
            <div class="logo">
                <img src="/src/assets/images/logo.png" alt="">
            </div>

            <layout-nav />
        </div>
    </div>
</template>

<script type="text/babel">
    import LayoutNav from './Nav'

    export default {
        components: { LayoutNav }
    }
</script>