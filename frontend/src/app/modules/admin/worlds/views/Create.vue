<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'globe')" class="margin-right-10">
            Worlds
            <span>Create a new World</span>
        </page-title>

        <panel>
            <world-form action="/admin/worlds" method="post" :data="world" />
        </panel>
    </page-load>
</template>

<script>
    import WorldForm from './Form'

    export default {
        components: { WorldForm },

        data () {
            return {
                world: {
                    name: '',
                    type: ''
                }
            }
        }
    }
</script>