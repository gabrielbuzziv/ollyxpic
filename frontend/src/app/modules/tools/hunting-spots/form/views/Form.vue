<template>
    <page-load id="hunting-spots" class="hunting-spots__form">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.list' }" class="btn" exact>
                    <i class="mdi mdi-chevron-left margin-right-5"></i>
                    Back
                </router-link>

                <button class="btn btn-primary" @click="autoSave">
                    <i class="mdi mdi-content-save margin-right-5"></i>
                    Save Draft
                </button>

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
                            <div class="form-group margin-bottom-0" v-show="huntVocations.length > 1">
                                <el-switch
                                        v-model="team"
                                        on-text=""
                                        off-text="">
                                </el-switch>
                                <label>Team Hunt</label>
                            </div>

                            <input type="hidden" name="soloable" v-model="soloable">
                        </div>
                    </panel>

                    <panel>
                        <div class="row margin-bottom-15">
                            <div class="form-group col-md-6">
                                <input type="text" name="title" class="form-control" placeholder="Title" v-model="title">
                            </div>

                            <div class="form-group col-md-6">
                                <input type="text" name="location" class="form-control" placeholder="Location" v-model="location">
                            </div>
                        </div>

                        <el-tabs v-model="activeName">

                            <!-- Description -->
                            <el-tab-pane label="Details" name="description">
                                <div class="form-group">
                                    <vue-summernote ref="description" :height="320"
                                                    placeholder="Insert here how this hunting spot works, give some suggestions ..."/>
                                    <input type="hidden" name="description" v-model="description">
                                </div>
                            </el-tab-pane>

                            <!-- Tips -->
                            <el-tab-pane label="Tips" name="tips">
                                <div class="form-group">
                                    <vue-summernote ref="tips" :height="320"
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
                                    <input type="hidden" :name="`supplies[${index}][item]`" v-model="supply.item.id">
                                    <input type="hidden" :name="`supplies[${index}][amount]`" v-model="supply.amount">
                                </template>
                            </el-tab-pane>

                            <!-- Equipments -->
                            <el-tab-pane label="Equipments" name="equipments">
                                <equipments :equipments="equipments" />

                                <template v-for="equipment, index in equipments">
                                    <input type="hidden" :name="`equipments[${index}][item]`" v-model="equipment.item.id">
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
                            <input type="text" name="author" class="form-control" placeholder="Author name" v-model="author">
                        </div>
                    </panel>
                </div>
            </div>
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
                title: '',
                location: '',
                experience: 1000000,
                profit: 50000,
                description: '',
                tips: '',
                team: false,
                task: false,
                quest: false,
                premium: true,
                creatures: [],
                supplies: [],
                equipments: [],
                author: '',
                interval: null
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
            },
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

                if (! this.validate())
                    return false

                services.save(new FormData(form))
                    .then(response => {
                        this.$message.success('Thanks! Your hunting spot has been submitted for review, soon we will read and active in Ollyxpic.')
                        this.$router.push({ name: 'tools.spots.list' })
                        this.resetDraft()
                    })
            },

            validate () {
                if (! this.huntVocations.length) {
                    this.$message.error('You need to choose a vocation.')
                    return false
                }

                if (! this.title.length) {
                    this.$message.error('Fill the title field.')
                    return false
                }

                if (! this.location.length) {
                    this.$message.error('Fill the location field.')
                    return false
                }

                if (! this.description.length) {
                    this.$message.error('Fill the details field.')
                    return false
                }

                if (! this.creatures.length) {
                    this.$message.error('You need to select at least one creature.')
                    return false
                }

                if (! this.supplies.length) {
                    this.$message.error('You need to add at least one supply.')
                    return false
                }

                return true
            },

            resetDraft () {
                localStorage.removeItem('hunting_spot.title');
                localStorage.removeItem('hunting_spot.location');
                localStorage.removeItem('hunting_spot.description');
                localStorage.removeItem('hunting_spot.tips');
                localStorage.removeItem('hunting_spot.experience');
                localStorage.removeItem('hunting_spot.profit');
                localStorage.removeItem('hunting_spot.team');
                localStorage.removeItem('hunting_spot.task');
                localStorage.removeItem('hunting_spot.premium');
                localStorage.removeItem('hunting_spot.quest');
                localStorage.removeItem('hunting_spot.author');
                localStorage.removeItem('hunting_spot.level');
                localStorage.removeItem('hunting_spot.vocations');
                localStorage.removeItem('hunting_spot.creatures');
                localStorage.removeItem('hunting_spot.supplies');
                localStorage.removeItem('hunting_spot.equipments');
            },

            loadDraft () {
                this.title = localStorage.getItem('hunting_spot.title')
                this.location = localStorage.getItem('hunting_spot.location')
                this.description = localStorage.getItem('hunting_spot.description')
                this.tips = localStorage.getItem('hunting_spot.tips')
                this.experience = parseInt(localStorage.getItem('hunting_spot.experience'))
                this.profit = parseInt(localStorage.getItem('hunting_spot.profit'))
                this.team = localStorage.getItem('hunting_spot.team') == 'true' ? true : this.team
                this.task = localStorage.getItem('hunting_spot.task') == 'true' ? true : this.task
                this.premium = localStorage.getItem('hunting_spot.premium') == 'true' ? true : this.premium
                this.quest = localStorage.getItem('hunting_spot.quest') == 'true' ? true : this.quest
                this.author = localStorage.getItem('hunting_spot.author')

                this.level = localStorage.getItem('hunting_spot.level') && localStorage.getItem('hunting_spot.level') != null
                    ? JSON.parse(localStorage.getItem('hunting_spot.level'))
                    : this.level

                this.huntVocations = localStorage.getItem('hunting_spot.vocations') && localStorage.getItem('hunting_spot.vocations') != null
                    ? JSON.parse(localStorage.getItem('hunting_spot.vocations'))
                    : this.huntVocations

                this.creatures = localStorage.getItem('hunting_spot.creatures') && localStorage.getItem('hunting_spot.creatures') != null
                    ? JSON.parse(localStorage.getItem('hunting_spot.creatures'))
                    : this.creatures

                this.supplies = localStorage.getItem('hunting_spot.supplies') && localStorage.getItem('hunting_spot.supplies') != null
                    ? JSON.parse(localStorage.getItem('hunting_spot.supplies'))
                    : this.supplies

                this.equipments = localStorage.getItem('hunting_spot.equipments') && localStorage.getItem('hunting_spot.equipments') != null
                    ? JSON.parse(localStorage.getItem('hunting_spot.equipments'))
                    : this.equipments

                Vue.nextTick(() => {
                    this.$refs.description.run('code', this.description)
                    this.$refs.tips.run('code', this.tips)
                })
            },

            autoSave () {
                localStorage.setItem('hunting_spot.title', this.title);
                localStorage.setItem('hunting_spot.location', this.location);
                localStorage.setItem('hunting_spot.description', this.description);
                localStorage.setItem('hunting_spot.tips', this.tips);
                localStorage.setItem('hunting_spot.experience', this.experience);
                localStorage.setItem('hunting_spot.profit', this.profit);
                localStorage.setItem('hunting_spot.team', this.team);
                localStorage.setItem('hunting_spot.task', this.task);
                localStorage.setItem('hunting_spot.premium', this.premium);
                localStorage.setItem('hunting_spot.quest', this.quest);
                localStorage.setItem('hunting_spot.author', this.author);
                localStorage.setItem('hunting_spot.level', JSON.stringify(this.level));
                localStorage.setItem('hunting_spot.vocations', JSON.stringify(this.huntVocations));
                localStorage.setItem('hunting_spot.creatures', JSON.stringify(this.creatures));
                localStorage.setItem('hunting_spot.supplies', JSON.stringify(this.supplies));
                localStorage.setItem('hunting_spot.equipments', JSON.stringify(this.equipments));

                this.$message.success('Draft saved')
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

            this.$store.dispatch('spots/FETCH_VOCATIONS')

            Vue.nextTick(() => {
                this.$refs.description.$on('onChange', content => this.description = content)
                this.$refs.tips.$on('onChange', content => this.tips = content)
            })

            this.loadDraft()

            this.interval = setInterval(() => this.autoSave(), 30000)
        },

        beforeDestroy () {
            clearInterval(this.interval)
        }
    }
</script>