<template>
    <div>
        <layout-topbar />
        <layout-header />
        <layout-content>
            <router-view></router-view>
        </layout-content>
        <layout-footer />
    </div>
</template>

<script type="text/babel">
    import LayoutTopbar from './default/Topbar'
    import LayoutHeader from './default/Header'
    import LayoutContent from './default/Content'
    import LayoutFooter from './default/Footer'

    export default {
        components: { LayoutTopbar, LayoutHeader, LayoutContent, LayoutFooter }
    }
</script>

<style lang="scss">
    @import './src/assets/scss/app';
</style>