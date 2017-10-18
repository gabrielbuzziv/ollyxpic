<template>
    <page-load id="home">
        <div class="row">
            <div class="col-md-9 news">
                <header>
                    <h3>Lastest news</h3>
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

                <router-link :to="{ name: 'calculators.waste' }" slot="anchor">
                    <card title="Supplies" subtitle="Waste">
                        <img :src="image_path('item', 3147)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.imbuements' }" slot="anchor">
                    <card title="Imbuements" subtitle="Waste/Time" dark>
                        <img :src="image_path('item', 2655)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.hunt' }" slot="anchor">

                    <card title="Hunts" subtitle="Waste/Profit">
                        <img :src="image_path('item', 296)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.loot.count' }" slot="anchor">

                    <card title="Loot" subtitle="Count">
                        <img :src="image_path('item', 93)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.loot.acumulator' }" slot="anchor">

                    <card title="Loot" subtitle="Acumulator" dark>
                        <img :src="image_path('item', 1613)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.blessing' }" slot="anchor">

                    <card title="Blessings" subtitle="Penalty">
                        <img :src="image_path('item', 1922)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.speedboost' }" slot="anchor">

                    <card title="Speed" subtitle="Boost">
                        <img :src="image_path('item', 1)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.spellcaster' }" slot="anchor">

                    <card title="Spellcaster" subtitle="Damage & Healing" dark>
                        <img :src="image_path('item', 117)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.mvp' }" slot="anchor">

                    <card title="Warzone" subtitle="MVP's">
                        <img :src="image_path('item', 862)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>

                <router-link :to="{ name: 'calculators.damage.protection' }" slot="anchor">

                    <card title="Damage" subtitle="Protection">
                        <img :src="image_path('item', 208)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
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