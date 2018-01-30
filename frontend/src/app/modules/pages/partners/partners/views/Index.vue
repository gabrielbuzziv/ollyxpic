<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'Cateroide\'s doll')" class="margin-right-10">
            <div class="title">
                <h2>Partners</h2>
                <span>Streamers etc</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-3" v-for="partner in sites">
                <panel>
                    {{ partner.name }}
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3" v-for="partner in streamers">
                <panel>
                    {{ partner.name }}
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                partners: []
            }
        },

        computed: {
            streamers () {
                return this.partners.filter(partner => partner.twitch != null && partner.twitch != '')
            },

            sites () {
                return this.partners.filter(partner => partner.site != null && partner.site != '')
            }
        },

        mounted () {
            services.fetchPartners()
                .then(response => this.partners = response.data)
        }
    }
</script>