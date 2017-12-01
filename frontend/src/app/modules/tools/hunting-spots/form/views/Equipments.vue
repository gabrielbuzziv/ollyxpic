<template>
    <div>
        <div class="equipments">
            <div class="row" v-for="equipment, index in equipments">
                <div class="form-group col-md-11" :class="{ 'col-md-12': index == 0 }">
                    <el-cascader
                            v-model="equipment.item"
                            :options="equipmentsCategories"
                            :props="equipmentsProps"
                            @active-item-change="getEquipments"
                            placeholder="Choose the Equipment">
                    </el-cascader>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-xs btn-delete" v-if="index > 0"
                            @click.prevent="removeEquipment(index)">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>

            <div class="equipments-options">
                <button class="btn btn-sm" @click.prevent="addEquipment">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    More Equipments
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import services from '../services'

    export default {
        props: ['equipments'],

        data () {
            return {
                equipmentsCategories: [
                    { label: 'Swords', equipments: [] },
                    { label: 'Axes', equipments: [] },
                    { label: 'Clubs', equipments: [] },
                    { label: 'Distance', equipments: [] },
                    { label: 'Wands', equipments: [] },
                    { label: 'Rods', equipments: [] },
                    { label: 'Shields', equipments: [] },
                    { label: 'Spellbooks', equipments: [] },
                    { label: 'Helmets', equipments: [] },
                    { label: 'Armors', equipments: [] },
                    { label: 'Legs', equipments: [] },
                    { label: 'Boots', equipments: [] },
                    { label: 'Tools', equipments: [] },
                ],
                equipmentsProps: { value: 'label', children: 'equipments' },
            }
        },

        watch: {
            equipments () {
                this.$emit('update:equipments', this.equipments)
            }
        },

        methods: {
            getEquipments (category) {
                const index = this.equipmentsCategories.map(category => category.label).indexOf(category[0])
                services.getEquipments(category[0])
                    .then(response => {
                        this.equipmentsCategories[index].equipments = response.data.map(equipment => {
                            return {
                                label: equipment.title
                            }
                        })
                    })
            },

            addEquipment () {
                this.equipments.push({ item: null })
            },

            removeEquipment (index) {
                this.equipments.splice(index, 1)
            },
        }
    }
</script>