<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.partners' }" tag="button" class="btn btn-rounded" exact>
                    <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Key of Numerous Locks')" class="margin-right-10">
            <div class="title">
                <h2>Partners</h2>
                <span>Create a new Partner</span>
            </div>
        </page-title>

        <partner-form action="admin/partners" method="post" :partner="partner" />
    </page-load>
</template>

<script>
    import PartnerForm from './Form'

    export default {
        components: { PartnerForm },

        data () {
            return {
                partner: {
                    name: '',
                    logo: '',
                    site: '',
                    twitch: '',
                    youtube: ''
                },
            }
        },
    }
</script>