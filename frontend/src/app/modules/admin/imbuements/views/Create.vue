<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.imbuements' }" class="btn btn-rounded" exact>
                    <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'silencer claws')" class="margin-right-10">
            <div class="title">
                <h2>Imbuements</h2>
                <span>Manage Imbuements</span>
            </div>
        </page-title>

        <panel>
            <imbuement-form action="/admin/imbuements" method="post" :data="imbuement" />
        </panel>
    </page-load>
</template>

<script>
    import ImbuementForm from './Form'

    export default {
        components: { ImbuementForm },

        data () {
            return {
                imbuement: {
                    title: '',
                    name: '',
                    description: '',
                    items: [
                        { item_id: '', imbuement_id: '', tier: 1, amount: '' },
                        { item_id: '', imbuement_id: '', tier: 2, amount: '' },
                        { item_id: '', imbuement_id: '', tier: 3, amount: '' },
                    ]
                }
            }
        }
    }
</script>