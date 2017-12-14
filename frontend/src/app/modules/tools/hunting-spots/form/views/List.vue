<template>
    <page-load id="hunting-spots" class="hunting-spots__list">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.form' }" class="btn btn-success">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    Add
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Map (Brown)')" alt="">
            <div class="title">
                <h2>Hunting</h2>
                <span>Spots</span>
            </div>
        </page-title>

        <filters :filters="filters" @change="filtersChanged" />

        <header class="page-filter">
            <form action="">
                <div class="pull-left">
                    <div class="paginator">
                        <button class="btn" :disabled="! hasPreviousPage" @click.prevent="prevPage">
                            <i class="mdi mdi-chevron-left"></i>
                        </button>

                        <button class="btn" :disabled="! hasNextPage" @click.prevent="nextPage">
                            <i class="mdi mdi-chevron-right"></i>
                        </button>
                    </div>

                    <h4>{{ pagination.total }} Results found</h4>
                </div>

                <div class="pull-right">
                    <el-select v-model="pagination.sort"
                               placeholder="Sort by">
                        <el-option v-for="sort in sorts" :value="sort.value" :label="sort.label"
                                   :key="sort.value"/>
                    </el-select>
                </div>

                <div class="clearfix"></div>
            </form>
        </header>

        <div class="spots">
            <spot :spot="spot" v-for="spot in spots" :key="spot.id" />
        </div>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import Filters from './list/Filters'
    import Spot from './list/Spot'
    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        components: { Filters, Spot },

        data () {
            return {
                filters: {
                    vocations: {
                        knight: false,
                        druid: false,
                        sorcerer: false,
                        paladin: false,
                    },
                    level: [0, 500],
                    experience: 10000,
                    profit: 0,
                    team: false,
                    task: false,
                    quest: false,
                    premium: true,
                },
                pagination: {
                    sort: 'experience:asc',
                    total: 0,
                    page: 1,
                    limit: 10
                },
                sorts: [
                    { value: 'level_min:asc', label: 'Level Ascending' },
                    { value: 'level_min:desc', label: 'Level Descending' },
                    { value: 'experience:asc', label: 'Experience Ascending' },
                    { value: 'experience:desc', label: 'Experience Descending' },
                    { value: 'profit:asc', label: 'Profit Ascending' },
                    { value: 'profit:desc', label: 'Profit Descending' },
                ],
                limits: [9, 15, 21],
                vocations: [],
                spots: []
            }
        },

        computed: {
            totalPages () {
                return this.pagination.total / this.pagination.limit
            },

            hasPreviousPage () {
                return this.pagination.page > 1
            },

            hasNextPage () {
                return this.pagination.page < this.totalPages
            }
        },

        watch: {
            'pagination.page' () {
                this.loadSpots()
            },

            'pagination.sort' () {
                this.loadSpots()
            },

            'pagination.limit' () {
                this.loadSpots()
                this.pagination.page = 1
            },
        },

        methods: {
            loadSpots () {
                const page = this.pagination.page
                const limit = this.pagination.limit
                const sort = this.pagination.sort
                const filters = this.filters

                services.getHuntingSpots(page, limit, sort, filters)
                    .then(response => {
                        this.pagination.total = response.data.total
                        this.spots = response.data.items
                    })
            },

            nextPage () {
                this.pagination.page ++
            },

            prevPage () {
                this.pagination.page --
            },

            filtersChanged: debounce (function () {
                this.loadSpots()
            }, 300)
        },

        beforeMount () {
            if (this.$route.query.hasOwnProperty('closed_beta_key') && this.$route.query.closed_beta_key == 'c3a1dbbd27368ff5edb3f718a7e95bbe') {
                localStorage.setItem('closed_beta_key', this.$route.query.closed_beta_key)
                this.$message.success('Closed Beta Key Activated')
            }

            if (! localStorage.getItem('closed_beta_key')
                || localStorage.getItem('closed_beta_key') == null
                || localStorage.getItem('closed_beta_key') != 'c3a1dbbd27368ff5edb3f718a7e95bbe') {
                this.$message.error('You closed beta key is not valid!')
                this.$router.push({ name: 'pages.home' })

                return false
            }
        },

        mounted () {
            this.loadSpots()
        }
    }
</script>