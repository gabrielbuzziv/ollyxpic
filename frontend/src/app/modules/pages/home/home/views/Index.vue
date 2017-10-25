<template>
    <page-load id="home">
        <div class="row">
        <div class="alert alert-warning">
            We are looking for tips about new calculators to Ollyxpic, Look <a href="http://ollyxpic.com/#/news/5">here</a>.
        </div>
            <div class="col-md-9 news">
                <header>
                    <h3>Latest news</h3>
                </header>

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

                    <span class="source" v-if="post.source">
                        Source:
                        <a :href="post.source" target="_blank">{{ post.source }}</a>
                    </span>
                </panel>

                <router-link :to="{ name: 'pages.news' }" class="btn btn-default btn-block">
                    Check more news
                </router-link>
            </div>

            <div class="col-md-3 calculators">
                <header>
                    <h3>Calculators</h3>
                </header>

                <router-link :to="{ name: 'calculators.imbuements' }" slot="anchor">
                    <card title="Imbuements" subtitle="Waste/Time" dark>
                        <img :src="image_path_by_name('item', 'silencer claws')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.loot.count' }" slot="anchor">
                    <card title="Loot" subtitle="Count">
                        <img :src="image_path_by_name('item', 'steel boots')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.blessing' }" slot="anchor">
                    <card title="Blessings" subtitle="Penalty">
                        <img :src="image_path_by_name('item', 'spiritual charm')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.speedboost' }" slot="anchor">
                    <card title="Speed" subtitle="Boost">
                        <img :src="image_path_by_name('item', 'boots of haste')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.spellcaster' }" slot="anchor">
                    <card title="Spellcaster" subtitle="Damage & Healing" dark>
                        <img :src="image_path_by_name('item', 'sudden death rune')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.mvp' }" slot="anchor">
                    <card title="Warzone" subtitle="MVP's">
                        <img :src="image_path_by_name('item', 'medal of honour')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <!--<router-link :to="{ name: 'calculators.damage.protection' }" slot="anchor">-->
                    <!--<card title="Damage" subtitle="Protection">-->
                        <!--<img :src="image_path_by_name('item', 'great shield')" slot="icon">-->
                        <!--<i class="mdi mdi-chevron-right"></i>-->
                    <!--</card>-->
                <!--</router-link>-->
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
                post: {
                    author: {}
                }
            }
        },

        methods: {
            load () {
                services.getPost()
                    .then(response => this.post = response.data)
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