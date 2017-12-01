<template>
    <page-load>
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'admin.news.list' }" class="btn btn-default">
                    <i class="mdi mdi-arrow-left"></i>
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'golden newspaper')" class="margin-right-10">
            <div class="title">
                <h2>News</h2>
                <span>Manage news</span>
            </div>
        </page-title>

        <panel>
            <news-form action="/admin/posts" method="post" :data="post" />
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
                    source: '',
                    active: 1,
                    created_at: ''
                }
            }
        }
    }
</script>