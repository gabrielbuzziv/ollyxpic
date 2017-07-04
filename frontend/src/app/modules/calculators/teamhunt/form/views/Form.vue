<template>
    <page-load>
        <page-title>
            Team Hunt Calculator
        </page-title>

        <div class="row">
            <div class="col-md-8">
                <ul class="tabs">
                    <tab-link tab="loot" active>
                        <img :src="item_path('steel-boots')" class="margin-right-5">
                        <span>Loot</span>
                    </tab-link>

                    <tab-link tab="waste">
                        <img :src="item_path('supreme-health-potion')" class="margin-right-5">
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
                            <form-textarea rows="12" v-model="loot"/>
                        </form-group>

                        <div class="alert alert-warning margin-bottom-0">
                            <p>
                                <i class="mdi mdi-information margin-right-5"></i>
                                Paste your <em>Loot Log</em> here of all teamhunt loot.
                            </p>
                        </div>
                    </tab-content>

                    <tab-content tab="waste">
                        <div class="alert alert-info">
                            Waste per party member.
                        </div>

                        <div class="inline" v-for="teammate, index in teammates">
                            <form-group>
                                <form-input :name="`teammates[${index}][name]`" v-model="teammate.name" placeholder="Character name" />
                            </form-group>

                            <form-group>
                                <form-input :name="`teammates[${index}][waste]`" v-model="teammate.waste" placeholder="Waste in gps" />
                            </form-group>

                            <div class="buttons">
                                <button class="btn-link">
                                    <i class="mdi mdi-calculator"></i>
                                </button>

                                <button class="btn-link" @click.prevent="add('teammates')">
                                    <i class="mdi mdi-plus"></i>
                                </button>

                                <button class="btn-link" @click.prevent="remove('teammates', index)" v-if="index > 0">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>

                            <el-dialog :visible>
                                Something
                            </el-dialog>
                        </div>
                    </tab-content>

                    <tab-content tab="filters">
                        <div class="alert alert-warning">
                            <p>
                                <i class="mdi mdi-information margin-right-5"></i>
                                To filter the items you can manage what kind of items do you want do be shown.
                            </p>
                        </div>

                        <form-group>
                            <form-checkbox label="Cost Effective" v-model="filters.effective"/>
                        </form-group>

                        <form-group>
                            <form-checkbox label="Stackable Items" v-model="filters.stackable"/>
                        </form-group>

                        <form-group>
                            <form-checkbox label="Gold Coins" v-model="filters.goldcoins"/>
                        </form-group>

                        <form-group>
                            <form-checkbox label="Valuables above" class="margin-right-10" v-model="filters.valuable"/>
                            <div class="input-group valuable">
                                <form-input v-model="filters.above" placeholder="2000" :disabled="!filters.valuable"/>
                                <div class="input-group-addon">gp</div>
                            </div>
                        </form-group>

                        <div class="alert alert-info margin-top-40 margin-bottom-0">
                            <p class="margin-bottom-15">
                                <b>Cost Effective:</b> items that the cost/capacity is worth than gold coin.<br>
                                <small>
                                    <em>
                                        e.g. Scale armor cost is 75 gp but the cap is 105 oz. The cost effective is 0.7,
                                        that is worth less then gold coin.
                                    </em>
                                </small>
                            </p>
                            <p class="margin-bottom-15"><b>Creature Products:</b> enable/disable stackable items from creatures.</p>
                            <p class="margin-bottom-15"><b>Gold Coins:</b> enable/disable gold coins.</p>
                            <p><b>Valuable above:</b> enable/disable a minimum value for the item to be calculated.</p>
                        </div>
                    </tab-content>

                    <div class="tab-footer">
                        <button class="btn btn-success btn-lg btn-block" type="submit" :disabled="calculating">
                            <span v-if="calculating">
                                <i class="mdi mdi-loading"></i>
                                Calculating
                            </span>
                            <span v-else>Calculate</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <panel title="Team Hunt Calculator" icon="calculator">
                    <p>How much times you loose your precious time calculating the profit/waste from a team hunt?</p>
                    <p>Now, your problems gone. The team hunt calculator provide you a flexible tool to calculate your
                        team waste and the hunt profit and then show how much money each own profited.</p>
                    <p>We garantee that your profit will increase, since we use the best formula to calculate the
                        profit.</p>
                </panel>

                <panel title="How it works" icon="information">
                    <p>This tool is flexible so you can use in many ways.</p>
                    <p>1. You can just calculate the value of total loot collected.</p>
                    <p>2. You can calculate the waste of your teammates and in the result see how much each own
                        profit.</p>
                    <p>3. You can filter the loot that you want to be calculated.</p>
                    <p>4. You can remove the items that you don't want.</p>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'

    export default {
        data () {
            return {
                calculating: false,
                loot: '',
                teammates: [{ name: null, waste: null  }],
                filters: {
                    effective: false,
                    stackable: true,
                    goldcoins: true,
                    valuable: false,
                    above: 1000
                }
            }
        },

        methods: {
            add (type) {
                this[type].push({ name: null, waste: null  })
            },

            remove (type, index) {
                if (this[type].length > 1) {
                    this[type].splice(index, 1)
                }
            },

            onSubmit () {
                this.calculating = true

                const data = {
                    loot: this.loot,
                    teammates: this.teammates,
                    filters: this.filters
                }

                services.calculate(data)
                        .then(response => {
                            this.calculating = false
                            this.$router.push({ name: 'calculators.teamhunt.result', params: { id: response.data.id } })
                        })
                        .catch(error => {
                            this.calculating = false
                        })
            }
        }
    }
</script>