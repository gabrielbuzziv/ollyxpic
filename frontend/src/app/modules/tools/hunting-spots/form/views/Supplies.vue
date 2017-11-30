<template>
    <div>
        <div class="supplies">
            <div class="row" v-for="supply, index in supplies">
                <div class="form-group col-md-4">
                    <el-cascader
                            v-model="supply.item"
                            :options="suppliesCategories"
                            :props="suppliesProps"
                            @active-item-change="getSupplies"
                            placeholder="Supply">
                    </el-cascader>
                </div>

                <div class="form-group col-md-3">
                    <el-input-number v-model="supply.amount" :min="1" :step="1"/>
                </div>

                <div class="form-group col-md-4">
                    <input type="text" class="form-control" placeholder="Description ...">
                </div>

                <div class="col-md-1">
                    <button class="btn btn-xs btn-delete" v-if="index > 0"
                            @click.prevent="removeSupply(index)">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>

            <div class="supplies-options">
                <button class="btn btn-sm" @click.prevent="addSupply">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    Add more supplies
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import services from '../services'

    export default {
        props: ['supplies'],

        data () {
            return {
                suppliesCategories: [
                    { label: 'Potions', supplies: [] },
                    { label: 'Rings', supplies: [] },
                    { label: 'Amulets', supplies: [] },
                    { label: 'Ammunitions', supplies: [] },
                ],
                suppliesProps: { value: 'label', children: 'supplies' },
            }
        },

        watch: {
            supplies () {
                this.$emit('update:supplies', this.supplies)
            }
        },

        methods: {
            getSupplies (category) {
                const index = this.suppliesCategories.map(category => category.label).indexOf(category[0])
                services.getSupplies(category[0])
                    .then(response => {
                        this.suppliesCategories[index].supplies = response.data.map(supply => {
                            return {
                                label: supply.title
                            }
                        })
                    })
            },

            addSupply () {
                this.supplies.push({ item: null, amount: 1, description: '' })
            },

            removeSupply (index) {
                this.supplies.splice(index, 1)
            },
        }
    }
</script>