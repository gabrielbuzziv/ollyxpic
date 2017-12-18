<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'steel boots')">
            <div class="title">
                <h2>Loot</h2>
                <span>Count</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <ul class="tabs">
                    <tab-link tab="loot" active>
                        <img :src="image_path_by_name('item', 'steel boots')" class="margin-right-5">
                        <span>Loot</span>
                    </tab-link>

                    <tab-link tab="waste">
                        <img :src="image_path_by_name('item', 'ultimate mana potion')" class="margin-right-5">
                        <span>Waste</span>
                    </tab-link>

                    <tab-link tab="filters">
                        <i class="mdi mdi-filter margin-right-5"></i>
                        <span>Filters</span>
                    </tab-link>
                </ul>

                <form action="" @submit.prevent="onSubmit" ref="form">

                    <tab-content tab="loot" active>

                        <form-group label="Loot Log">
                            <form-textarea rows="12" v-model="loot" placeholder="Paste your loot log here"/>
                        </form-group>

                        <form-group class="margin-bottom-0">
                            <el-checkbox v-model="look_at" label="true">
                                Look at

                                <el-tooltip content="To turn look items readable.">
                                    <i class="mdi mdi-information margin-left-5"></i>
                                </el-tooltip>
                            </el-checkbox>
                        </form-group>
                    </tab-content>

                    <tab-content tab="waste">
                        <div class="margin-top-10"></div>
                        <div class="block row" v-for="teammate, index in teammates">
                            <form-group columns="3">
                                <form-input :name="`teammates[${index}][name]`" :data="teammate.name"
                                            v-model="teammate.name" placeholder="Character name"/>
                            </form-group>

                            <form-group columns="3">
                                <form-input :name="`teammates[${index}][waste]`" :data="teammate.waste"
                                            v-model="teammate.waste" placeholder="Waste in gps"/>
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link" @click.prevent="add('teammates')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('teammates', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </tab-content>

                    <tab-content tab="filters">
                        <div class="margin-top-10"></div>

                        <form-group>
                            <form-checkbox label="Cost Effective" v-model="filters.effective"/>
                            <el-tooltip placement="right" class="margin-left-10">
                                <p slot="content">
                                    <b>Cost Effective:</b> items that the cost/capacity is worth than gold coin.<br>
                                    <small>
                                        <em>
                                            e.g. Scale armor cost is 75 gp but the cap is 105 oz. The cost effective is 0.7,
                                            that is worth less then gold coin.
                                        </em>
                                    </small>
                                </p>
                                <i class="mdi mdi-information"></i>
                            </el-tooltip>
                        </form-group>

                        <form-group>
                            <form-checkbox label="Stackable Items" v-model="filters.stackable"/>
                            <el-tooltip placement="right" class="margin-left-10">
                                <p slot="content">
                                    <b>Creature Products:</b> enable/disable stackable items from creatures.
                                </p>
                                <i class="mdi mdi-information"></i>
                            </el-tooltip>
                        </form-group>

                        <form-group>
                            <form-checkbox label="Gold Coins" v-model="filters.goldcoins"/>
                            <el-tooltip placement="right" class="margin-left-10">
                                <p slot="content">
                                    <b>Gold Coins:</b> enable/disable gold coins.
                                </p>
                                <i class="mdi mdi-information"></i>
                            </el-tooltip>
                        </form-group>

                        <form-group>
                            <form-checkbox label="Valuables above" class="margin-right-10" v-model="filters.valuable"/>
                            <div class="input-group valuable">
                                <form-input v-model="filters.above" placeholder="2000" :disabled="!filters.valuable"/>
                                <div class="input-group-addon">gp</div>
                            </div>
                            <el-tooltip placement="right" class="margin-left-10">
                                <p slot="content">
                                    <b>Valuable above:</b> enable/disable a minimum value for the item to be calculated.
                                </p>
                                <i class="mdi mdi-information"></i>
                            </el-tooltip>
                        </form-group>
                    </tab-content>
                </form>
            </div>
        </div>

        <button class="btn btn-success btn-lg btn-block margin-top-20"
                type="submit"
                :disabled="calculating"
                @click="onSubmit">
            <span v-if="calculating">
                <i class="mdi mdi-loading"></i>
                Calculating
            </span>
            <span v-else>Calculate</span>
        </button>

        <supplies-calculator :visible.sync="suppliesCalculator"/>
    </page-load>
</template>

<script type="text/babel">
    import SuppliesCalculator from './SuppliesCalculator'
    import services from '../services'

    export default {
        components: { SuppliesCalculator },

        data () {
            return {
                selectedTeammate: null,
                calculating: false,
                loot: '',
                look_at: false,
                teammates: [{ name: null, waste: null, supplies: [] }],
                filters: {
                    effective: false,
                    stackable: true,
                    goldcoins: true,
                    valuable: false,
                    above: 1000
                },
                suppliesCalculator: false
            }
        },

        methods: {
            calculateSupplies (teammate) {
                this.suppliesCalculator = true
                this.selectedTeammate = this.teammates.indexOf(teammate)

                this.$root.$emit('teammate::supply', teammate)
            },

            closeCalculateSupplies (teammate) {
                this.suppliesCalculator = false

                this.$root.$emit('teammate::supply', null)
            },

            add (type) {
                this[type].push({ name: null, waste: null, supplies: [] })
            },

            remove (type, index) {
                if (this[type].length > 1) {
                    this[type].splice(index, 1)
                }
            },

            onSubmit () {
                const data = {
                    loot: this.loot,
                    teammates: this.teammates,
                    filters: this.filters,
                    loot_at: this.look_at
                }

                if (data.loot == '' || data.loot == null) {
                    this.$message.error('You need to add the loot log to calculate.')
                    return false
                }

                this.calculating = true
                services.calculate(data)
                    .then(response => {
                        this.calculating = false
                        this.$router.push({
                            name: 'tools.loot.count.result',
                            params: { id: response.data.id },
                            query: { password: response.data.password }
                        })
                    })
                    .catch(error => {
                        this.calculating = false
                    })
            }
        },

        mounted () {
            this.$root.$on('waste::teammate', teammate => {
                this.teammates[this.selectedTeammate] = teammate
            })
        },

        beforeDestroy () {
            this.$root.$off('waste::teammate')
        }
    }
</script>
