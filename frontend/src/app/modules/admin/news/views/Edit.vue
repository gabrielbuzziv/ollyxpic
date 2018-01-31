<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.news.list' }" class="btn btn-rounded" exact>
                    <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                    Back
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'golden newspaper')" class="margin-right-10">
            <div class="title">
                <h2>News: {{ post.title }}</h2>
                <span>Manage news</span>
            </div>
        </page-title>

        <panel>
            <news-form :action="`/admin/posts/${$route.params.id}`" method="patch" :data="post" v-if="! loading" />
        </panel>
    </page-load>
</template>

<script>
    import NewsForm from './Form'
    import services from '../services'

    export default {
        components: { NewsForm },

        data () {
            return {
                post: {
                    title: '',
                    body: '',
                    source: '',
                    active: 1,
                    created_at: null,
                    hotnews: false,
                    link: '',
                    comments: ''
                },
                loading: true
            }
        },

        mounted () {
            services.find(this.$route.params.id)
                .then(response => {
                    this.post = response.data
                    this.loading = false
                })
                .catch(() => this.loading = false)
        }
    }
</script>