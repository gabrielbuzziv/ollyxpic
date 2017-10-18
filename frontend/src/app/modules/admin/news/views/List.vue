<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.news.create' }" class="btn btn-default">
                    <i class="mdi mdi-plus margin-right-5"></i>
                    Create
                </router-link>
            </div>

            <img :src="image_path('item', 1291)" class="margin-right-10">
            News
            <span>Manage news</span>
        </page-title>

        <panel>
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="post in news">
                    <td>{{ post.title }}</td>
                    <td>{{ post.author.name }}</td>
                    <td>{{ post.created_at }}</td>
                    <td class="text-right">
                        <button class="btn btn-sm">
                            <i class="mdi mdi-eye"></i>
                        </button>

                        <router-link :to="{ name: 'admin.news.edit', params: { id: post.id } }" class="btn btn-sm"
                                     tag="button">
                            <i class="mdi mdi-pencil"></i>
                        </router-link>

                        <button class="btn btn-sm" @click.prevent="remove(post)">
                            <i class="mdi mdi-delete"></i>
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