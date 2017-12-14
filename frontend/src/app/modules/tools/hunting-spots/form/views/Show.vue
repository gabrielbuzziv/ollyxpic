<template>
    <page-load id="hunting-spots" class="hunting-spots__inner" :loading="loading">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.list' }" class="btn" exact>
                    <i class="mdi mdi-chevron-left margin-right-5"></i>
                    Back to List
                </router-link>
            </div>

            <img :src="image_path('creature', creature)" alt="">
            <div class="title">
                <h2>{{ spot.title }}</h2>
                <span>by {{ author }}</span>
            </div>
        </page-title>

        <div class="row features">
            <div class="col-md-3">
                <panel>
                    <span class="name">Level Required</span>
                    <b>
                        {{ spot.level_min }} - {{ spot.level_max }}
                    </b>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    <span class="name">Experience</span>
                    <b>{{ spot.experience | experience }}</b>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    <span class="name">Profit</span>
                    <b>{{ spot.profit | profit }}</b>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    <span class="name">Vocations</span>

                    <span class="label label-primary" v-for="vocation in spot.vocations" :key="vocation.id">
                        {{ vocation.title }}
                    </span>
                </panel>
            </div>
        </div>

        <el-tabs type="card" v-model="tabs">
            <el-tab-pane label="Details & Tips" name="description">
                <panel>
                    <div class="content">
                        <div class="content-text" v-html="spot.description"></div>

                        <div class="tags">
                            <el-tooltip content="Task Available" placement="top" v-if="spot.has_task">
                                <i class="mdi mdi-format-list-checks"></i>
                            </el-tooltip>

                            <el-tooltip content="Require Premium Account" placement="top" v-if="spot.require_premium">
                                <i class="mdi mdi-crown"></i>
                            </el-tooltip>

                            <el-tooltip content="Require Quest" placement="top" v-if="spot.require_quest">
                                <i class="mdi mdi-treasure-chest"></i>
                            </el-tooltip>

                            <el-tooltip content="Solo Hunting" placement="top" v-if="spot.soloable">
                                <i class="mdi mdi-account"></i>
                            </el-tooltip>

                            <el-tooltip content="Team Hunting" placement="top" v-if="! spot.soloable">
                                <i class="mdi mdi-account-multiple"></i>
                            </el-tooltip>
                        </div>

                        <h3 class="margin-top-20">Location</h3>
                        <p>{{ spot.location }}</p>

                        <div class="margin-top-40" v-if="spot.tips">
                            <h3>Tips</h3>
                            <div class="content-text" v-html="spot.tips"></div>
                        </div>
                    </div>
                </panel>
            </el-tab-pane>

            <el-tab-pane label="Supplies & Equipments" name="supplies">
                <supplies :supplies="spot.supplies" :equipments="spot.equipments"/>
            </el-tab-pane>

            <el-tab-pane label="Creatures & Loot" name="loot">
                <creatures :creatures="spot.creatures"/>
            </el-tab-pane>
        </el-tabs>
    </page-load>
</template>

<script>

    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import Supplies from './show/Supplies'
    import Creatures from './show/Creatures'
    import services from '../services'

    export default {
        components: { Supplies, Creatures },

        data () {
            return {
                tabs: 'description',
                loading: true,
                spot: {}
            }
        },

        computed: {
            creature () {
                return this.spot.creatures && this.spot.creatures.length ? this.spot.creatures[0].id : 0
            },

            author () {
                return this.spot.author == 'null' || this.spot.author == null || this.spot.author == '' ? 'Ollyxpic' : this.spot.author
            }
        },

        filters: {
            experience (value) {
                const data = value != null && value != '' ? value.format() : 0
                return `${data} exp/h`
            },

            profit (value) {
                const data = value != null && value != '' ? value.format() : 0
                return value > 0 ? `${data} gp/h` : 'Waste'
            }
        },

        mounted () {
            if (! localStorage.getItem('closed_beta_key')
                || localStorage.getItem('closed_beta_key') == null
                || localStorage.getItem('closed_beta_key') != 'c3a1dbbd27368ff5edb3f718a7e95bbe') {
                this.$message.error('You closed beta key is not valid!')
                this.$router.push({ name: 'pages.home' })

                return false
            }

            services.getHuntingSpot(this.$route.params.id)
                .then(response => {
                    this.spot = response.data
                    this.loading = false
                })
                .catch(() => {
                    this.$router.push({ name: 'tools.spots.list' })
                    this.loading = false
                })
        }
    }
</script>