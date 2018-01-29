<template>
    <panel class="deaths">
        <h4>Recent Deaths</h4>

        <table class="table">
            <tbody>
                <death :death="death" :key="index" v-for="death, index in deaths" />
            </tbody>
        </table>
    </panel>
</template>

<script>
    import Death from './Death'

    export default {
        props: ['deaths'],

        components: { Death },
    }
</script>