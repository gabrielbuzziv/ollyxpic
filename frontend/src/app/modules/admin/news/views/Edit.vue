<template>
    <page-load>
        <page-title>
            <img :src="image_path('item', 1291)" class="margin-right-10">
            News: {{ post.title }}
            <span>Edit post</span>
        </page-title>

        <panel>
            <news-form :action="`/posts/${$route.params.id}`" method="patch" :data="post" v-if="! loading" />
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
                    created_at: null
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