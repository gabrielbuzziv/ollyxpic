<template>
    <page-load id="huntingspots">
        <page-title>
            <img :src="image_path_by_name('item', 'map')" alt="" class="margin-right-5">
            Hunting
            <span>Spots</span>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <form-group :columns="6"><!-- multiple works within -->
                        <el-select
                                v-model="form.huntingspot"
                                placeholder="Huntingspots"
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
            <div class="row">
                <div class="col-md-12">
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
                        <panel class="creatures">
                        </panel>
                    </div>

                    <div class="col-md-8">
                        <center>
                            <td v-for="creatures in huntingspot.creatures">
                                <el-tooltip :content="creatures.name">
                                    <img :src="image_path_by_name('creature', creatures.name)" class="image">
                                </el-tooltip>           
                            </td>
                        </center>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <panel class="items">
                    <center>
                        <td v-for="itemdrops in huntingspot.itemdrops" v-if="itemdrops.percentage < 0.9">
                            <el-tooltip :content="itemdrops.itemid">
                                    <img :src="image_path_by_name('item', itemdrops.itemid)" class="image">
                            </el-tooltip> 
                        </td>
                    </center>
                </panel>
            </div>

            <div class="col-md-12">
                <panel title="Misc info">
                    <h4>Supplies</h4>
                    {{ huntingspot.supplies }} <br>
                    <h4>Description</h4>
                    {{ huntingspot.description }}<br><br>
                    <h6><i>submitted by: {{ huntingspot.submitter }}</i></h6>
                </panel>
            </div>
        </div>

        <div class="row" v-if="! huntingspot.id">
            <div class="col-md-12">
                <panel title="Information">
                    This tool is great for finding new and fun spots to hunt at, just search for a monster or spot and if there<br>
                    is any available in our database it will show up in a list, Once you select one some information about that<br>
                    hunting spot will appear.<br><br>
                    Should you want to submit a huntingspot you can use our form to fill in all the information.

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
                    itemdrops: { id: '', itemid: '', percentage: '', },
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
