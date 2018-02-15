<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.news.create' }" class="btn btn-success btn-rounded">
                    <i class="mdi mdi-plus-circle margin-right-10"></i>
                    Add new
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'golden newspaper')" class="margin-right-10">
            <div class="title">
                <h2>News</h2>
                <span>Manage news</span>
            </div>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="post in news">
                    <td>
                        <span class="label label-danger" v-if="post.hotnews">Hotnews</span>
                        <span class="label label-primary" v-else>News</span>
                    </td>
                    <td>
                        <div class="limit_text">
                            {{ post.title }}
                        </div>
                    </td>
                    <td>{{ post.author.name }}</td>
                    <td>{{ post.created_at | dateForHuman }}</td>
                    <td class="text-right">
                        <router-link :to="{ name: 'admin.news.edit', params: { id: post.id } }" class="btn btn-xs"
                                     tag="button">
                            <i class="mdi mdi-pencil-circle"></i>
                        </router-link>

                        <button class="btn btn-xs" @click.prevent="remove(post)">
                            <i class="mdi mdi-close-circle"></i>
                        </button>
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
                news: []
            }
        },

        filters: {
            dateForHuman (date) {
                return moment.tz(date, "DD-MM-YYYY HH:mm:ss", 'America/New_York').fromNow()
            }
        },

        methods: {
            load () {
                services.fetchPosts()
                    .then(response => {
                        this.news = response.data
                    })
            },

            remove (post) {
                this.$confirm(`If you remove the post "${post.title}", you will not be able to recovery.`, 'Are you sure about this?', {
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, delete it',
                    type: 'error',
                }).then(() => {
                    services.remove(post.id)
                        .then(response => {
                            this.$message.success(`The post "${post.title}" has been removed.`)
                            this.load()
                        })
                })
            }
        },

        mounted () {
            this.load()
        }
    }
</script>