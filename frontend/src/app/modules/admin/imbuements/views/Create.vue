<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'silencer claws')" class="margin-right-10">
            Imbuements
            <span>Manage Imbuements</span>
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