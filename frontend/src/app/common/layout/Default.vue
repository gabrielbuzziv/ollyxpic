<template>
    <div>
        <layout-topbar />
        <layout-header />
        <layout-content>
            <router-view></router-view>
        </layout-content>
        <layout-footer />

        <layout-login />
    </div>
</template>

<script type="text/babel">
    import LayoutTopbar from './default/Topbar'
    import LayoutHeader from './default/Header'
    import LayoutContent from './default/Content'
    import LayoutFooter from './default/Footer'
    import LayoutLogin from './default/Login'

    export default {
        components: { LayoutTopbar, LayoutHeader, LayoutContent, LayoutFooter, LayoutLogin }
    }
</script>

<style lang="scss">
    @import './src/assets/scss/app';
</style>