<template>
    <div id="header">
        <div class="container">
            <div class="logo col-md-12">
                <div class="col-lg-4 col-sm-4">
                    <img src="/src/assets/images/logo.png" alt="">
                </div>

                <div class="pull-right">
                    <a href="http://www.tibia.com/community/?subtopic=fansites" target="_blank">
                        <img src="/src/assets/images/supported.gif" alt="">
                    </a>
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
