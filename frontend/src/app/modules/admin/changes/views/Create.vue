<template>
    <page-load>
        <page-title>
            <img :src="image_path('item', 357)" class="margin-right-10">
            Change Log
            <span>Add a new change</span>
        </page-title>

        <panel>
            <changes-form action="/changes" method="post" :data="change" />
        </panel>
    </page-load>
</template>

<script>
    import ChangesForm from './Form'

    export default {
        components: { ChangesForm },

        data () {
            return {
                change: {
                    title: '',
                    body: '',
                    created_at: ''
                }
            }
        }
    }
</script>