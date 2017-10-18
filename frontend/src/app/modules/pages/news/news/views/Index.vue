<template>
    <page-load id="news">
        <div class="col-md-8">
            <panel class="main-post">
                <template slot="heading">
                    <div class="pull-left">
                        <h3 class="panel-title">
                            {{ post.title }}
                        </h3>
                    </div>

                    <div class="clearfix"></div>
                </template>

                <small class="author">
                    Posted by {{ post.author.name }}
                    {{ getDateForHuman(post.created_at) }}
                </small>
                <div v-html="post.body"></div>
            </panel>

            <panel title="Comments">
                <disqus shortname="ollyxpic" :title="post.title" :identifier="disqus_url" :url="disqus_url"></disqus>
            </panel>
        </div>

        <div class="col-md-4">
            <panel class="list" title="Other posts">
                <ul class="posts" >
                    <li class="post" v-for="post in news" :key="post.id">
                        <router-link :to="{ name: 'pages.news', params: { id: post.id } }">
                            {{ post.title }}
                        </router-link>
                        <small>
                            ({{ getDateForHuman(post.created_at) }})
                        </small>
                    </li>
                </ul>
            </panel>
        </div>
    </page-load>
</template>

<script>
    import services from '../services'
    import { isEmpty } from 'lodash'

    export default {
        data () {
            return  {
                post: {
                    author: {}
                },
                news: [],
                disqus_url: location.href
            }
        },

        watch: {
            '$route.params.id' () {
                setTimeout(() => this.load(), 1)
                this.disqus_url = location.href
            }
        },

        methods: {
            load () {
                const id = this.$route.params.id || null

                services.getPost(id)
                    .then(response => this.post = response.data)

                services.fetchNews()
                    .then(response => this.news = response.data)
            },

            getDateForHuman (date) {
                return moment.tz(date, "DD-MM-YYYY HH:mm:ss", 'America/New_York').fromNow()
            }
        },

        mounted () {
            this.load()
        }
    }
</script>