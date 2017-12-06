<template>
    <page-load id="hunting-spots">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.form' }" class="btn btn-success">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    Submit a new
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Map (Brown)')" alt="">
            <div class="title">
                <h2>Hunting</h2>
                <span>Spots</span>
            </div>
        </page-title>


        <div class="row">
            <div class="col-md-3">
                <panel>
                    Filtro
                </panel>
            </div>

            <div class="col-md-9">
                <div class="list">
                    <panel>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Level</th>
                                    <th>Experience</th>
                                    <th>Profit</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="spot in spots">
                                    <td>
                                        <img :src="image_path('creatures', getSpotCreature(spot))" alt="">
                                    </td>
                                    <td>{{ spot.title }}</td>
                                    <td>
                                        {{ spot.level_min }}
                                        <i class="mdi mdi-arrow-right"></i>
                                        {{ spot.level_max }}
                                    </td>
                                    <td>{{ spot.experience | experience }}</td>
                                    <td>{{ spot.profit | profit }}</td>
                                    <td class="text-right">
                                        <router-link :to="{ name: 'tools.spots.show', params: { id: spot.id } }" class="btn btn-xs">
                                            <i class="mdi mdi-chevron-right"></i>
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </panel>
                </div>
            </div>
        </div>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import services from '../services'

    export default {
        data () {
            return {
                spots: []
            }
        },

        filters: {
            experience (value) {
                const data = value.format()
                return `${data} /h`
            },

            profit (value) {
                const data = value.format()
                return `${data} /h`
            },
        },

        methods: {
            getSpotCreature (spot) {
                return spot.creatures && spot.creatures.length ? spot.creatures[0].id : 0
            }
        },

        mounted () {
            services.getHuntingSpots()
                .then(response => {
                    this.spots = response.data
                })
        }
    }
</script>