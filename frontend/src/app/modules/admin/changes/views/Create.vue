<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.changes.list' }" class="btn btn-rounded" exact>
                    <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'scroll of heroic deeds')" class="margin-right-10">
            <div class="title">
                <h2>Change Log</h2>
                <span>Manage changes</span>
            </div>
        </page-title>

        <panel>
            <changes-form action="/admin/changes" method="post" :data="change" />
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