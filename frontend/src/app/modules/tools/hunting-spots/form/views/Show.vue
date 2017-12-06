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
                <span>by {{ spot.author || 'OllyxPic' }}</span>
            </div>
        </page-title>

        <div class="row features">
            <div class="col-md-3">
                <panel>
                    <span>Level Required</span>
                    <b>
                        {{ spot.level_min }}
                        <i class="mdi mdi-arrow-right"></i>
                        {{ spot.level_max }}
                    </b>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    <span>Experience</span>
                    <b>{{ spot.experience | experience }}</b>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    <span>Profit</span>
                    <b>{{ spot.profit | profit }}</b>
                </panel>
            </div>

            <div class="col-md-3">
                <panel>
                    <span>Location</span>
                    <b>{{ spot.location }}</b>
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <el-tabs type="card" v-model="tabs">
                    <el-tab-pane label="Details & Tips" name="description">
                        <panel>
                            <div class="content">
                                <div class="content-text" v-html="spot.description"></div>

                                <h3>Tips</h3>
                                <div class="content-text" v-html="spot.tips"></div>
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
            </div>

            <div class="col-md-2">
                <panel class="requirements">
                    <ul>
                        <li>
                            <span>Task Available?</span>
                            <b class="label" :class="[spot.has_task ? 'label-success' : 'label-danger']">{{ spot.has_task ? 'Yes' : 'No' }}</b>
                        </li>

                        <li>
                            <span>Require Quest?</span>
                            <b class="label" :class="[spot.require_quest ? 'label-success' : 'label-danger']">{{ spot.require_quest ? 'Yes' : 'No' }}</b>
                        </li>

                        <li>
                            <span>Require Premium?</span>
                            <b class="label" :class="[spot.require_premium ? 'label-success' : 'label-danger']">{{ spot.require_premium ? 'Yes' : 'No' }}</b>
                        </li>


                    </ul>
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
            }
        },

        filters: {
            experience (value) {
                const data = value.format()
                return `${data} exp/h`
            },

            profit (value) {
                const data = value.format()
                return value > 0 ? `${data} gp/h` : 'Waste'
            }
        },

        mounted () {
            services.getHuntingSpot(this.$route.params.id)
                .then(response => {
                    this.spot = response.data
                    this.loading = false
                })
                .catch(() => this.loading = false)
        }
    }
</script>