<template>
    <tr :class="{ 'open': detailsOpened }">
        <td>{{ world.name }}</td>
        <td>{{ world.type }}</td>
        <td>{{ buyPrice | currency }}</td>
        <td>{{ sellPrice | currency }}</td>
        <td>{{ lastUpdate || '-' }}</td>
        <td class="text-right">
            <router-link :to="{ name: 'tools.currencies.world', params: { id: world.id } }"
                         class="btn btn-xs btn-success"
                         exact
                         v-if="buyPrice">
                More Details
            </router-link>
        </td>
    </tr>
</template>

<script>
    import services from '../../services'
    import { isEmpty } from 'lodash'

    export default {
        props: ['world'],

        data () {
            return {
                detailsOpened: false,
                currencies: null,
                tab: 'chart'
            }
        },

        computed: {
            buyPrice () {
                return this.world.currencies && this.world.currencies.length
                    ? this.world.currencies[0].buy
                    : 0
            },

            sellPrice () {
                return this.world.currencies && this.world.currencies.length
                    ? this.world.currencies[0].sell
                    : 0
            },

            lastUpdate () {
                return this.world.currencies && this.world.currencies.length
                    ? moment(this.world.currencies[0].created_at).fromNow()
                    : 0
            }
        },

        filters: {
            currency (data) {
                return data ? `${data.format()} gp` : '-'
            }
        },

        methods: {
            toggleDetails () {
                this.detailsOpened = ! this.detailsOpened

                if (this.detailsOpened)
                    this.loadDetails()
            },

            loadDetails () {
                services.getLastCurrencies(this.world.id)
                    .then(response => {
                        this.currencies = response.data
                    })
            }
        }
    }
</script>