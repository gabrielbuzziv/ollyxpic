<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.worlds' }" class="btn btn-default">
                    <i class="mdi mdi-arrow-left"></i>
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'globe')" class="margin-right-10">
            <div class="title">
                <h2>Worlds</h2>
                <span>Manage Worlds</span>
            </div>
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