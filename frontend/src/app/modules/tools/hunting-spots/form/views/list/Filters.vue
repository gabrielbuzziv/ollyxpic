<template>
    <div class="filters">
        <panel>
            <div class="row">
                <!-- Vocation -->
                <div class="vocation col-md-3">
                    <label>Vocation</label>

                    <el-checkbox v-model="filters.vocations[vocation.title.toLowerCase()]"
                                 v-for="vocation in vocations"
                                 :key="vocation.id">
                        {{ vocation.title }}
                    </el-checkbox>
                </div>

                <!-- Level -->
                <div class="sliders col-md-4">
                    <div class="level">
                        <label>
                            Level Range

                            <div class="slider-values">
                                {{ levelMin }} - {{ levelMax }}
                            </div>
                        </label>

                        <el-slider
                                v-model="filters.level"
                                :show-tooltip="false"
                                range
                                :min="0"
                                :max="500"
                                :step="10"
                                @change="$emit('change')">
                        </el-slider>
                    </div>

                    <div class="experience">
                        <label>
                            Experience

                            <div class="slider-values">
                                {{ experience }}
                            </div>
                        </label>

                        <el-slider
                                v-model="filters.experience"
                                :show-tooltip="false"
                                :min="10000"
                                :max="3000000"
                                :step="10000"
                                @change="$emit('change')">
                        </el-slider>
                    </div>
                </div>

                <!-- Option -->
                <div class="options col-md-2 col-md-offset-2">
                    <label>Options:</label>
                    <el-checkbox v-model="filters.team">Team Hunt</el-checkbox>
                    <el-checkbox v-model="filters.task">Task Available</el-checkbox>
                    <el-checkbox v-model="filters.quest">Not Require Quest</el-checkbox>
                    <el-checkbox v-model="filters.premium">Require Premium</el-checkbox>
                </div>
            </div>
        </panel>
    </div>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../../services'

    export default {
        props: ['filters'],

        data () {
            return {
                vocations: []
            }
        },

        computed: {
            levelMin () {
                return this.filters.level[0]
            },

            levelMax () {
                return this.filters.level[1] == 500 ? `${this.filters.level[1]}+` : this.filters.level[1]
            },

            experience () {
                const data = this.filters.experience.format()
                return this.filters.experience == 3000000 ? `${data}+ /h` : `${data} /h`
            }
        },

        watch: {
            'filters.vocations.knight' () {
                this.$emit('change')
            },

            'filters.vocations.druid' () {
                this.$emit('change')
            },

            'filters.vocations.sorcerer' () {
                this.$emit('change')
            },

            'filters.vocations.paladin' () {
                this.$emit('change')
            },

            'filters.team' () {
                this.$emit('change')
            },

            'filters.task' () {
                this.$emit('change')
            },

            'filters.quest' () {
                this.$emit('change')
            },

            'filters.premium' () {
                this.$emit('change')
            },
        },

        methods: {
            loadVocations () {
                services.getVocations()
                    .then(response => this.vocations = response.data)
            }
        },

        mounted () {
            this.loadVocations()
        }
    }
</script>