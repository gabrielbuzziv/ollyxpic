<template>
    <page-load id="currencies">
        <page-title>
            <img :src="image_path_by_name('item', 'tibia coins')" class="margin-right-5">
            <div class="title">
                <h2>Tibia Currency</h2>
                <span>Stock Exchange</span>
            </div>
        </page-title>

        <div class="filters">
            <div class="pull-left">
                <el-select v-model="filters.type"
                           placeholder="All Types"
                           clearable
                           @change="load">
                    <el-option v-for="type in types" :value="type" :label="type" :key="type"/>
                </el-select>
            </div>

            <div class="pull-right">
                <el-select v-model="sort"
                           placeholder="Sort By"
                           @change="load">
                    <el-option v-for="sort in sorts" :value="sort.value" :label="sort.label" :key="sort.value"/>
                </el-select>
            </div>
        </div>

        <div class="clearfix"></div>

        <panel class="margin-top-30">
            <table class="table margin-bottom-0">
                <thead>
                    <tr>
                        <th>World</th>
                        <th>World Type</th>
                        <th>Buy Currency</th>
                        <th>Sell Currency</th>
                        <th>Last Update</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <worlds :world="world" :key="world.id" v-for="world in worlds"/>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import Worlds from './list/World'
    import services from '../services'

    export default {
        components: { Worlds },

        data () {
            return {
                loading: true,
                worlds: [],
                types: ['Open PvP', 'Optional PvP', 'Retro Open PvP', 'Retro Hardcore PvP'],
                filters: {
                    type: '',
                },
                sort: 'name:asc',
                sorts: [
                    { value: 'name:asc', label: 'Name Ascending' },
                    { value: 'name:desc', label: 'Name Descending' },
                ]
            }
        },

        methods: {
            load () {
                const sort = this.sort
                const filters = this.filters

                services.fetchWorlds(sort, filters)
                    .then(response => {
                        this.worlds = response.data
                        this.loading = false
                    })
                    .catch(() => this.loading = false)
            },

            getLastCurrency (world) {
                return world.currencies[0]
            },

            compareLastTwoCurrencies (world, currency) {
                if (world.currencies.length > 1) {
                    const last = world.currencies[0]
                    const penult = world.currencies[1]

                    return (100 - ((penult[currency] * 100) / last[currency]))
                }
            },

            getDateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'America/New_York').fromNow()
            },
        },

        mounted () {
            this.load()
        }
    }
</script>