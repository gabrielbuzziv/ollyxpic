<template>
    <page-load>
        <page-title>
            <img :src="image_path('item', 1291)" class="margin-right-10">
            News
            <span>Add a new post</span>
        </page-title>

        <panel>
            <news-form action="/posts" method="post" :data="post" />
        </panel>
    </page-load>
</template>

<script>
    import NewsForm from './Form'

    export default {
        components: { NewsForm },

        data () {
            return {
                post: {
                    title: '',
                    body: '',
                    active: 1,
                    created_at: ''
                }
            }
        }
    }
</script>