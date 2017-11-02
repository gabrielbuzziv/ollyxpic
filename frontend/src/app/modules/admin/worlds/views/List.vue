<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.worlds.create' }" class="btn btn-default">
                    <i class="mdi mdi-plus margin-right-5"></i>
                    Create
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'globe')" class="margin-right-10">
            Worlds
            <span>Manage Worlds</span>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Last Update</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="world in worlds">
                        <td>{{ world.name }}</td>
                        <td>{{ world.type }}</td>
                        <td>
                            <span v-if="world.currencies && world.currencies.length">
                                {{ getDateForHuman(world.currencies[0].created_at) }}
                            </span>
                        </td>
                        <td width="480">
                            <img :src="image_path_by_name('item', 'tibia coins')" class="inline margin-right-10" />

                            <form action="" class="form-inline inline" @submit.prevent="addCurrency(world)">
                                <div class="form-group">
                                    <input type="text" name="buy" class="form-control" placeholder="Buy" v-model="world.buy" @keypress.13="addCurrency(world)">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="sell" class="form-control" placeholder="Sell" v-model="world.sell" @keypress.13="addCurrency(world)">
                                </div>
                            </form>
                        </td>
                        <td class="text-right">
                            <router-link :to="{ name: 'admin.worlds.edit', params: { id: world.id } }"
                                         class="btn btn-xs">
                                <i class="mdi mdi-pencil-circle"></i>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </panel>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                loading: false,
                worlds: [],
                currencies: {}
            }
        },

        methods: {
            load () {
                services.fetchWorlds()
                    .then(response => {
                        this.worlds = response.data.map(world => {
                            return {
                                ...world,
                                buy: '',
                                sell: ''
                            }
                        })
                    })
            },

            getDateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'America/New_York').fromNow()
            },

            addCurrency (world) {
                if (world.buy == null || world.buy == '' || world.sell == null || world.sell == '') {
                    this.$message.error('Fill Buy and Sell currency.')

                    return false
                }

                const currencies = { buy: world.buy, sell: world.sell }

                services.addCurrency(world.id, currencies)
                    .then(response => {
                        this.$message.success('Currency added with success.')
                        this.load()
                    })
            }

//            remove (post) {
//                this.$confirm(`If you remove the post "${post.title}", you will not be able to recovery.`, 'Are you sure about this?', {
//                    cancelButtonText: 'Cancel',
//                    confirmButtonText: 'Yes, delete it',
//                    type: 'error',
//                }).then(() => {
//                    services.remove(post.id)
//                        .then(response => {
//                            this.$message.success(`The post "${post.title}" has been removed.`)
//                            this.load()
//                        })
//                })
//            }
        },

        mounted () {
            this.load()
        }
    }
</script>