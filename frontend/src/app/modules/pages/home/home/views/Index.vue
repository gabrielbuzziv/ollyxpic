<template>
    <page-load id="home">

        <!--<panel>-->
        <!--<h4>Welcome</h4>-->
        <!--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur dolores eveniet expedita.</p>-->
        <!--</panel>-->

        <div class="row">
            <div class="col-md-8">
                <panel class="news">
                    <header>
                        <router-link :to="{ name: 'pages.news', params: { slug: latestNews.slug } }">
                            <h4>{{ latestNews.title }}</h4>
                        </router-link>
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

                <div class="row">
                    <div class="col-md-6" v-for="news in olderNews">
                        <panel class="features">
                            <img :src="image_path_by_name('item', 'golden newspaper')" alt="">

                            <router-link :to="{ name: 'pages.news', params: { slug: news.slug } }"
                                         class="read">
                                {{ news.title }}
                            </router-link>
                        </panel>
                    </div>
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

                <panel class="discord">
                    <a href="https://discord.gg/RnzuGcV" target="_blank">
                        <img src="src/assets/images/discord.png" alt="">
                        Join us on Discord
                    </a>
                </panel>
            </div>
        </div>

        <tools/>
    </page-load>
</template>

<script>
    import Tools from './Tools'
    import services from '../services'
    import { isEmpty } from 'lodash'

    export default {
        components: { Tools },

        data () {
            return {
                news: [],
                hotnews: []
            }
        },

        computed: {
            latestNews () {
                return this.news.length ? this.news[0] : {}
            },

            olderNews () {
                return this.news.splice(1, 2)
            }
        },

        filters: {
            dateForHuman (date) {
                return moment.tz(date, "DD-MM-YYYY HH:mm:ss", 'America/New_York').fromNow()
            }
        },

        methods: {
            getLatestNews () {
                services.getPosts(3)
                    .then(response => {
                        this.news = response.data
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
