<template>
    <div id="header">
        <div class="container">
            <div class="logo col-md-12">
                <div class="col-md-4">
                    <img src="/src/assets/images/logo.png" alt="">
                </div>
            </div>

            <layout-nav/>

        </div>

    </div>
</template>

<script type="text/babel">
    import LayoutNav from './Nav'

    export default {
        components: { LayoutNav }
    }
</script>
