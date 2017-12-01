<template>
    <page-load id="hunting-spots" class="hunting-spots__inner" :loading="loading">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.list' }" class="btn" exact>
                    <i class="mdi mdi-chevron-left margin-right-5"></i>
                    Back to List
                </router-link>
            </div>

            <img :src="image_path('creature', spot.creatures[0].id)" alt="">
            <div class="title">
                <h2>{{ spot.title }}</h2>
                <span>by {{ spot.author || 'OllyxPic' }}</span>
            </div>
        </page-title>

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
                        <supplies :supplies="spot.supplies"/>
                    </el-tab-pane>

                    <el-tab-pane label="Creatures & Loot" name="loot">
                        <creatures :creatures="spot.creatures"/>
                    </el-tab-pane>

                    <el-tab-pane label="Pictures & Videos" name="gallery">
                        <panel>
                            Gallery
                        </panel>
                    </el-tab-pane>
                </el-tabs>
            </div>

            <div class="col-md-2"></div>
        </div>
    </page-load>
</template>

<script>
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

        filters: {
            experience (value) {
                return `${value} exp/h`
            },

            profit (value) {
                return value > 0 ? `${value} gp/h` : 'Waste'
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