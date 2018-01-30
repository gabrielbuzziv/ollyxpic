<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'book of orc language')" class="margin-right-10">
            <div class="title">
                <h2>Translations</h2>
                <span>Manage Translations</span>
            </div>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>
                            <input type="text" class="form-control" placeholder="From" v-model="from">
                        </th>
                        <th>
                            <input type="text" class="form-control" placeholder="To" v-model="to">
                        </th>
                        <th class="text-right">
                            <button class="btn btn-sm btn-success btn-rounded" @click.prevent="addTranslation()">
                                <i class="mdi mdi-plus-circle margin-right-10"></i>
                                Add
                            </button>
                        </th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <translation :translation="translation"
                                 v-for="translation in translations"
                                 :key="translation.id"
                                 @updated="load()"/>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import Translation from './Translation'
    import services from '../services'

    export default {
        components: { Translation },

        data () {
            return {
                loading: false,
                translations: [],
                from: '',
                to: '',
            }
        },

        methods: {
            load () {
                services.fetchTranslations()
                    .then(response => {
                        this.translations = response.data
                    })
            },

            addTranslation () {
                const data = {
                    from: this.from,
                    to: this.to,
                    fixed: 1
                }

                services.add(data)
                    .then(response => {
                        this.$message.success('Translation added.')
                        this.from = ''
                        this.to = ''

                        this.load()
                    })
            }
        },

        mounted () {
            this.load()
        }
    }
</script>