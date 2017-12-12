<template>
    <page-load id="hunting-spots" class="hunting-spots__list">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.form' }" class="btn btn-success">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    Submit a new
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Map (Brown)')" alt="">
            <div class="title">
                <h2>Hunting</h2>
                <span>Spots</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-8">
                <header class="page-filter">
                    <form action="">
                        <div class="pull-left">
                            <el-select v-model="pagination.sort"
                                       placeholder="Sort by">
                                <el-option v-for="sort in sorts" :value="sort.value" :label="sort.label"
                                           :key="sort.value"/>
                            </el-select>

                            <el-select v-model="pagination.limit"
                                       placeholder="Sort by"
                                       style="width: 70px;">
                                <el-option v-for="limit in limits" :value="limit" :label="limit" :key="limit"/>
                            </el-select>
                        </div>

                        <div class="pull-right">
                            <div class="pagiantion">
                                <button class="btn" :disabled="! hasPreviousPage" @click.prevent="prevPage">
                                    <i class="mdi mdi-chevron-left"></i>
                                </button>

                                <button class="btn" :disabled="! hasNextPage" @click.prevent="nextPage">
                                    <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </form>
                </header>

                <div class="spots row">
                    <div class="col-md-6" v-for="spot in spots">
                        <panel class="spot">
                            <header>
                                <div class="thumb">
                                    <!--<img :src="image_path('creature', getSpotCreature(spot))"-->
                                    <!--v-if="getSpotCreature(spot)">-->
                                </div>

                                <div class="info">
                                    <span class="title">
                                        {{ spot.title }}
                                    </span>

                                    <div class="vocations">
                                        <span class="label label-primary" v-for="vocation in spot.vocations">
                                            {{ vocation.title }}
                                        </span>
                                    </div>
                                </div>
                            </header>

                            <section>
                                <div class="features">
                                    <ul>
                                        <li>
                                            Level: <span>{{ spot.level_min }} - {{ spot.level_max }}</span>
                                        </li>

                                        <li>
                                            Experience: <span>{{ spot.experience | experience }}</span>
                                        </li>

                                        <li>
                                            Profit: <span>{{ spot.profit | profit }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tags">
                                    <span class="title">With:</span>
                                    <el-tooltip content="Require Premium" placement="top" v-if="spot.require_premium">
                                        <span class="label label-success">Premium</span>
                                    </el-tooltip>

                                    <el-tooltip content="Require Quest" placement="top" v-if="spot.require_quest">
                                        <span class="label label-success">Quest</span>
                                    </el-tooltip>

                                    <el-tooltip content="Task Available" placement="top" v-if="spot.has_task">
                                        <span class="label label-success">Task</span>
                                    </el-tooltip>

                                    <el-tooltip content="Team Hunt" placement="top" v-if="! spot.soloable">
                                        <span class="label label-success">Team</span>
                                    </el-tooltip>
                                </div>

                                <router-link :to="{ name: 'tools.spots.show', params: { id: spot.id } }"
                                             class="btn btn-block btn-more">
                                    Read more
                                </router-link>
                            </section>
                        </panel>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <panel class="filters">
                    <form action="">
                        <div class="form-group bordered">
                            <el-select v-model="filters.vocation"
                                       no-data-text="Vocations not found"
                                       placeholder="Vocation"
                                       clearable
                                       @change="changeFilters">
                                <el-option v-for="vocation in vocations" :value="vocation.id" :label="vocation.title"
                                           :key="vocation.id"/>
                            </el-select>
                        </div>

                        <div class="form-group">
                            <span class="slider-label">
                                <label>Level</label>
                                <span class="value">
                                    {{ filters.level[0] }}
                                    <i class="mdi mdi-chevron-right"></i>
                                    {{ filters.level[1] }}
                                </span>
                            </span>

                            <el-slider
                                    v-model="filters.level"
                                    range
                                    :min="0"
                                    :max="1000"
                                    :step="10"
                                    :show-tooltip="false"
                                    @change="changeFilters">
                            </el-slider>

                            <input type="hidden" name="level_min" v-model="filters.level[0]">
                            <input type="hidden" name="level_max" v-model="filters.level[1]">
                        </div>

                        <div class="form-group">
                            <span class="slider-label">
                                <label>Experience <small>(above)</small></label>
                                <span class="value">{{ expHour }}</span>
                            </span>

                            <el-slider
                                    v-model="filters.experience"
                                    :min="0"
                                    :max="5000000"
                                    :step="500000"
                                    :show-tooltip="false"
                                    @change="changeFilters">
                            </el-slider>

                            <input type="hidden" name="experience" class="form-control" v-model="filters.experience">
                        </div>

                        <div class="form-group margin-bottom-0">
                            <span class="slider-label">
                                <label>Profit <small>(above)</small></label>
                                <span class="value">{{ profitHour }}</span>
                            </span>

                            <el-slider
                                    v-model="filters.profit"
                                    :min="0"
                                    :max="1000000"
                                    :step="50000"
                                    :show-tooltip="false"
                                    @change="changeFilters">
                            </el-slider>

                            <input type="hidden" name="profit" class="form-control" v-model="filters.profit">
                        </div>
                    </form>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../services'
    import { debounce } from 'lodash'

    export default {
        data () {
            return {
                filters: {
                    vocation: '',
                    level: [0, 100],
                    experience: 0,
                    profit: 0,
                },
                pagination: {
                    sort: 'experience:asc',
                    total: 0,
                    page: 1,
                    limit: 10
                },
                sorts: [
                    { value: 'experience:asc', label: 'Experience Ascending' },
                    { value: 'experience:desc', label: 'Experience Descending' },
                    { value: 'profit:asc', label: 'Profit Ascending' },
                    { value: 'profit:desc', label: 'Profit Descending' },
                ],
                limits: [10, 20, 30],
                vocations: [],
                spots: []
            }
        },

        computed: {
            expHour () {
                const experience = this.filters.experience.format()
                return `${experience} exp/h`
            },

            profitHour () {
                const profit = this.filters.profit.format()
                return this.filters.profit > 0 ? `${profit} gp/h` : 'Waste'
            },

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

        filters: {
            experience (value) {
                const data = value.format()
                return `${data} /h`
            },

            profit (value) {
                const data = value.format()
                return `${data} /h`
            },
        },

        methods: {
            getSpotCreature (spot) {
                return spot.creatures && spot.creatures.length
                    ? spot.creatures[0].id
                    : false
            },

            changeFilters: debounce(function () {
                this.loadSpots()
            }, 300),

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

            loadFilters () {
                services.getVocations()
                    .then(response => {
                        this.vocations = response.data
                    })
                    .catch(() => this.vocations = [])
            },

            nextPage () {
                this.pagination.page ++
            },

            prevPage () {
                this.pagination.page --
            }
        },

        mounted () {
            this.loadFilters()
            this.loadSpots()
        }
    }
</script>