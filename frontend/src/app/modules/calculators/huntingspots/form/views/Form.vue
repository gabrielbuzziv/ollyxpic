<template>
    <page-load id="huntingspots">
        <page-title>
            <img :src="image_path_by_name('item', 'map')" alt="" class="margin-right-5">
            Hunting
            <span>Spots1</span>
        </page-title>


        <div class="row" v-if="! loading">
            <div class="col-md-12">
                
                    <div class="row">
                        <form-group :columns="6"><!-- multiple works within -->
                            <el-select
                                    v-model="form.huntingspot"
                                    placeholder="Huntspots"
                                    class="creature-select"
                                    :remote-method="getHuntingSpots"
                                    remote
                                    filterable
                                    clearable
                                    default-first-option
                                    @change="selectHuntingSpot">
                                <el-option
                                        v-for="huntingspot in form.huntingspots"
                                        :key="huntingspot.id"
                                        :label="huntingspot.title"
                                        :value="huntingspot.id"
                                        >
                                </el-option>
                            </el-select>
                        </form-group>
                    </div>
                
            </div>
        </div>


        <div class="row" v-if="huntingspot.id > 0">
            <div class="col-md-4">
                <panel v-bind:title="huntingspot.title">
                    <div class="status">

                        Average exp/h: {{ huntingspot.exp }} <br>
                        Average Profit: {{ huntingspot.profit }} <br>
                        Recomended Level: {{ huntingspot.level }} <br>
                        Vocations: {{ huntingspot.vocation }} <br>

                    </div>
                </panel>
            </div>

            <div class="col-md-8">
                <panel title="Creatures">
                    <td v-for="creatures in huntingspot.creatures">
                        <el-tooltip :content="creatures.name">
                            <img :src="image_path('creature', creatures.id)" class="image">
                        </el-tooltip>             
                    </td>
                </panel>
            </div>

            <div class="col-md-12">
                <panel title="Loot">
                    <td v-for="itemdrops in huntingspot.itemdrops" :rowspan="5">
                            <img :src="image_path('item', itemdrops.itemid)" class="image">
                    </td>
                </panel>
            </div>

            <div class="col-md-12">
                <panel title="Misc info">
                    <h4>Description</h4>
                    {{ huntingspot.description }}<br>
                    <h4>Supplies</h4>
                    {{ huntingspot.supplies }} <br>
                    <h4>Valuable Loot</h4>
                    {{ huntingspot.valuableloot }} <br><td v-for="creatures in huntingspot.creatures"> {{ creatures.name }} </td>
                </panel>
            </div>
        </div>


    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                loading: false,
                huntingspot: '',
                    itemdrops: { id: '', creatureid: '', itemid: '', },
                    creatures: { id: '', title: '', name: '', },                    
            
                form: {
                    huntingspot: '',
                    huntingspots: [],
                }
            }
        },

        computed: {

        },

        methods: {

            getHuntingSpots: debounce (function (query) {
                if (! isEmpty(query)) {
                    services.getHuntingSpots(query)
                            .then(response => {
                                this.form.huntingspots = response.data
                            })
                }
            }, 300),

            selectHuntingSpot (huntingspot) {
                services.getHuntingSpot(huntingspot)
                        .then(response => {
                            this.huntingspot = response.data
                        })
                        .catch(error => {
                            this.huntingspot = false
                        })
            },
        }
    }
</script>
