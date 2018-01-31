<template>
    <page-load id="news">
        <page-title>
            <img :src="image_path_by_name('item', 'golden newspaper')" class="margin-right-10">
            <div class="title">
                <h2>News</h2>
                <span>All about Tibia</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-8">
                <panel class="news">
                    <header>
                        <h4>{{ latestNews.title }}</h4>
                    </header>

                    <article v-html="latestNews.body"/>

                    <span class="source" v-if="latestNews.source">
                        <b>Source:</b>
                        <a :href="latestNews.source" target="_blank">
                            {{ latestNews.source }}
                        </a>
                    </span>

                    <footer>
                        <span class="comments" v-if="latestNews.comments">
                            <a :href="latestNews.comments" target="_blank">
                                <i class="mdi mdi-comment margin-right-5"></i>
                                Comments
                            </a>
                        </span>

                        <span class="date">
                            <i class="mdi mdi-calendar margin-right-5"></i>
                            {{ latestNews.created_at | dateForHuman }}
                        </span>
                    </footer>
                </panel>

                <div class="pull-left" v-if="previous">
                    <router-link :to="{ name: 'pages.news', params: { slug: previous.slug } }" class="btn btn-rounded" tag="button">
                        <i class="mdi mdi-arrow-left-bold-circle margin-right-10"></i>
                        Previous
                    </router-link>
                </div>

                <div class="pull-right" v-if="next">
                    <router-link :to="{ name: 'pages.news', params: { slug: next.slug } }" class="btn btn-rounded" tag="button">
                        <i class="mdi mdi-arrow-right-bold-circle margin-right-10"></i>
                        Next
                    </router-link>
                </div>
            </div>

            <div class="col-md-4">
                <panel class="hotnews">
                    <h4>Hotnews</h4>
                    <ul>
                        <li v-for="hot in hotnews">
                            <a :href="hot.link" :title="hot.title" target="_blank">
                                <img :src="image_path_by_name('item', 'Scroll of Ascension (Used)')"/>

                                {{ hot.title }}
                            </a>
                        </li>
                    </ul>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script>
    import services from '../services'
    import { isEmpty } from 'lodash'

    export default {
        data () {
            return {
                hotnews: [],
                latestNews: {},
                next: {},
                previous: {}
            }
        },

        watch: {
            '$route.params.slug' () {
                this.getLatestNews()
            }
        },

        filters: {
            dateForHuman (date) {
                return moment.tz(date, "DD-MM-YYYY HH:mm:ss", 'America/New_York').fromNow()
            }
        },

        methods: {
            getLatestNews () {
                const post = this.$route.params.slug ? this.$route.params.slug : null

                services.getPost(post)
                    .then(response => {
                        this.latestNews = response.data.post
                        this.next = response.data.next
                        this.previous = response.data.previous
                    })
            },

            getHotNews () {
                services.getHotnews(10)
                    .then(response => {
                        this.hotnews = response.data
                    })
            },
        },

        mounted () {
            this.getLatestNews()
            this.getHotNews()
        }
    }
</script>