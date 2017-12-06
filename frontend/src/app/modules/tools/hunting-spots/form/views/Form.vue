<template>
    <page-load id="hunting-spots">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.list' }" class="btn" exact>
                    <i class="mdi mdi-chevron-left margin-right-5"></i>
                    Back
                </router-link>

                <button class="btn btn-success" @click="onSubmit">
                    <i class="mdi mdi-check-circle margin-right-5"></i>
                    Submit
                </button>
            </div>

            <img :src="image_path_by_name('item', 'Map (Brown)')" alt="">
            <div class="title">
                <h2>Hunting</h2>
                <span>Spots</span>
            </div>
        </page-title>

        <form action="" class="hunting-spot-form" @submit.prevent="onSubmit" ref="form">
            <div class="row">
                <div class="col-md-8">
                    <panel>
                        <div class="form-group vocations" :class="{ 'margin-bottom-0': huntVocations.length <= 1 }">
                            <el-checkbox-group v-model="huntVocations">
                                <el-checkbox-button v-for="vocation in vocations" :value="vocation.id" :label="vocation.title"
                                                    :key="vocation.id"/>
                            </el-checkbox-group>

                            <input type="hidden" name="vocations[]" :value="vocation" v-for="vocation in huntVocations">
                        </div>

                        <div class="options">
                            <div class="form-group margin-bottom-0" v-if="huntVocations.length > 1">
                                <el-switch
                                        v-model="team"
                                        on-text=""
                                        off-text="">
                                </el-switch>
                                <label>Team Hunt</label>

                                <input type="hidden" name="soloable" v-model="soloable">
                            </div>
                        </div>
                    </panel>

                    <panel>
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="Title">
                        </div>

                        <div class="form-group">
                            <input type="text" name="location" class="form-control" placeholder="Location">
                        </div>

                        <el-tabs v-model="activeName">

                            <!-- Description -->
                            <el-tab-pane label="Details" name="description">
                                <div class="form-group">
                                    <vue-summernote ref="description" :height="200"
                                                    placeholder="Insert here how this hunting spot works, give some suggestions ..."/>
                                    <input type="hidden" name="description" v-model="description">
                                </div>
                            </el-tab-pane>

                            <!-- Tips -->
                            <el-tab-pane label="Tips" name="tips">
                                <div class="form-group">
                                    <vue-summernote ref="tips" :height="200"
                                                    placeholder="Insert here some tips and recommendations."/>
                                    <input type="hidden" name="tips" v-model="tips">
                                </div>
                            </el-tab-pane>

                            <!-- Creatures -->
                            <el-tab-pane label="Creatures" name="creatures">
                                <creatures :creatures="creatures" />
                                <input type="hidden" name="creatures[]" v-model="creature.id" v-for="creature in creatures">
                            </el-tab-pane>

                            <!-- Supplies -->
                            <el-tab-pane label="Supplies" name="supplies">
                                <supplies :supplies="supplies" />

                                <template v-for="supply, index in supplies">
                                    <input type="hidden" :name="`supplies[${index}][item]`" v-model="supply.item">
                                    <input type="hidden" :name="`supplies[${index}][amount]`" v-model="supply.amount">
                                    <input type="hidden" :name="`supplies[${index}][description]`" v-model="supply.description">
                                </template>
                            </el-tab-pane>

                            <!-- Equipments -->
                            <el-tab-pane label="Equipments" name="equipments">
                                <equipments :equipments="equipments" />

                                <template v-for="equipment, index in equipments">
                                    <input type="hidden" :name="`equipments[${index}][item]`" v-model="equipment.item">
                                </template>
                            </el-tab-pane>
                        </el-tabs>
                    </panel>
                </div>

                <div class="col-md-4">
                    <panel>
                        <div class="form-group">
                            <span class="slider-label">
                                <label>Level</label>
                                <span class="value">
                                    {{ level[0] }}
                                    <i class="mdi mdi-chevron-right"></i>
                                    {{ level[1] }}
                                </span>
                            </span>

                            <el-slider
                                    v-model="level"
                                    range
                                    :min="0"
                                    :max="1000"
                                    :step="10"
                                    :show-tooltip="false">
                            </el-slider>
                        </div>

                        <input type="hidden" name="level_min" v-model="level[0]">
                        <input type="hidden" name="level_max" v-model="level[1]">
                    </panel>

                    <panel>
                        <div class="form-group">
                            <span class="slider-label">
                                <label>Experience</label>
                                <span class="value">{{ expHour }}</span>
                            </span>

                            <el-slider
                                    v-model="experience"
                                    :min="100000"
                                    :max="5000000"
                                    :step="100000"
                                    :show-tooltip="false">
                            </el-slider>

                            <input type="hidden" name="experience" class="form-control" v-model="experience">
                        </div>

                        <div class="form-group margin-bottom-0">
                            <span class="slider-label">
                                <label>Profit</label>
                                <span class="value">{{ profitHour }}</span>
                            </span>

                            <el-slider
                                    v-model="profit"
                                    :min="0"
                                    :max="1500000"
                                    :step="10000"
                                    :show-tooltip="false">
                            </el-slider>

                            <input type="hidden" name="profit" class="form-control" v-model="profit">
                        </div>
                    </panel>

                    <panel title="Options" class="options">
                        <div class="form-group">
                            <el-switch
                                    v-model="task"
                                    on-text=""
                                    off-text="">
                            </el-switch>
                            <label>Tasks Available</label>

                            <input type="hidden" name="has_task" v-model="task">
                        </div>

                        <div class="form-group">
                            <el-switch
                                    v-model="quest"
                                    on-text=""
                                    off-text="">
                            </el-switch>
                            <label>Require Quest</label>

                            <input type="hidden" name="require_quest" v-model="quest">
                        </div>

                        <div class="form-group margin-bottom-0">
                            <el-switch
                                    v-model="premium"
                                    on-text=""
                                    off-text="">
                            </el-switch>
                            <label>Require Premium</label>

                            <input type="hidden" name="require_premium" v-model="premium">
                        </div>
                    </panel>

                    <panel>
                        <div class="form-group margin-bottom-0">
                            <input type="text" name="author" class="form-control" placeholder="Author name">
                        </div>
                    </panel>
                </div>
            </div>

            <button class="btn btn-success btn-block" type="submit">
                <i class="mdi mdi-check-circle margin-right-5"></i>
                Submit
            </button>
        </form>

    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import Creatures from './Creatures'
    import Supplies from './Supplies'
    import Equipments from './Equipments'
    import services from '../services'

    export default {
        components: { Creatures, Supplies, Equipments },

        data () {
            return {
                activeName: 'description',
                huntVocations: [],
                level: [0, 100],
                experience: 1000000,
                profit: 50000,
                description: '',
                tips: '',
                team: false,
                task: false,
                quest: false,
                premium: true,
                creatures: [],
                supplies: [{ item: null, amount: 1, description: '' }],
                equipments: [{ item: null }],
            }
        },

        computed: {
            vocations () {
                return this.$store.getters['spots/GET_VOCATIONS']
            },

            expHour () {
                const experience = this.experience.format()
                return `${experience} exp/h`
            },

            profitHour () {
                const profit = this.profit.format()
                return this.profit > 0 ? `${profit} gp/h` : 'Waste'
            },

            soloable () {
                return ! this.team
            }
        },

        watch: {
            huntVocations () {
                if (this.huntVocations.length <= 1) {
                    this.team = false
                }
            }
        },

        methods: {
            onSubmit () {
                const form = this.$refs.form

                services.save(new FormData(form))
                    .then(response => {
                        console.log(response.data)
                    })
            },
        },

        mounted () {
            this.$store.dispatch('spots/FETCH_VOCATIONS')

            Vue.nextTick(() => {
                this.$refs.description.$on('onChange', content => this.description = content)
                this.$refs.tips.$on('onChange', content => this.tips = content)
            })
        }
    }
</script>