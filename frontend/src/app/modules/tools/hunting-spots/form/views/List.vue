<template>
    <page-load id="hunting-spots">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.form' }" class="btn btn-default">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    Share your spot
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Map (Brown)')" alt="">
            <div class="title">
                <h2>Hunting</h2>
                <span>Spots</span>
            </div>
        </page-title>


        <div class="row">
            <div class="col-md-12">
                <div class="list">
                    <panel>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Level</th>
                                    <th>Experience</th>
                                    <th>Profit</th>
                                    <th>Options</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="spot in spots">
                                    <td>{{ spot.title }}</td>
                                    <td>
                                        {{ spot.level_min }}
                                        <i class="mdi mdi-arrow-right"></i>
                                        {{ spot.level_max }}
                                    </td>
                                    <td>{{ spot.experience | experience }}</td>
                                    <td>{{ spot.profit | profit }}</td>
                                    <td></td>
                                    <td>
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
    import services from '../services'

    export default {
        data () {
            return {
                spots: []
            }
        },

        filters: {
            experience (value) {
                return `${value} /h`
            },
            profit (value) {
                return `${value} /h`
            },
        },

        mounted () {
            services.getHuntingSpots()
                .then(response => {
                    this.spots = response.data
                })
        }
    }
</script>