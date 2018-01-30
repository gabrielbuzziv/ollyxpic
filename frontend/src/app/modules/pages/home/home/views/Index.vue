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
                        <h4>{{ latestNews.title }}</h4>
                    </header>

                    <article v-html="latestNews.body" />

                    <footer>
                        <span class="comments">
                            <i class="mdi mdi-comment margin-right-5"></i>
                            Comments
                        </span>

                        <span class="author">
                            <i class="mdi mdi-account margin-right-5"></i>
                            Posted by {{ latestNews.author.name }}
                            {{ latestNews.created_at | dateForHuman }}
                        </span>
                    </footer>
                </panel>

                <div class="row">
                    <div class="col-md-6" v-for="news in olderNews">
                        <panel class="features">
                            <h4>{{ news.title }}</h4>
                        </panel>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <panel class="hotnews">
                    <h4>Hotnews</h4>
                    <ul>
                        <li>Lorem ipsum dolor sit amet.</li>
                        <li>Lorem ipsum dolor sit amet.</li>
                        <li>Lorem ipsum dolor sit amet.</li>
                        <li>Lorem ipsum dolor sit amet.</li>
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

        <div class="row tools">
            <h4>Tools</h4>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.imbuements' }" slot="anchor">
                    <card title="Imbuements" subtitle="Waste/Time">
                        <img :src="image_path_by_name('item', 'silencer claws')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.loot.count' }" slot="anchor">
                    <card title="Loot" subtitle="Count">
                        <img :src="image_path_by_name('item', 'steel boots')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.blessing' }" slot="anchor">
                    <card title="Blessings" subtitle="Penalty">
                        <img :src="image_path_by_name('item', 'spiritual charm')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.speedboost' }" slot="anchor">
                    <card title="Speed" subtitle="Boost">
                        <img :src="image_path_by_name('item', 'boots of haste')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.damage.healing' }" slot="anchor">
                    <card title="Damage Calc" subtitle="Damage & Healing" dark>
                        <img :src="image_path_by_name('item', 'sudden death rune')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.expshare' }" slot="anchor">
                    <card title="Exp" subtitle="Share">
                        <img :src="image_path_by_name('item', 'purple tome')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.capcount' }" slot="anchor">
                    <card title="Cap" subtitle="Count">
                        <img :src="image_path_by_name('item', 'blossom bag')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.damage.protection' }" slot="anchor">
                    <card title="Damage" subtitle="Protection">
                        <img :src="image_path_by_name('item', 'great shield')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.currencies' }" slot="anchor">
                    <card title="Tibia Currency" subtitle="Stock Exchange">
                        <img :src="image_path_by_name('item', 'tibia coins')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.blacklist' }" slot="anchor">
                    <card title="Blacklist" subtitle="Quick Looting">
                        <img :src="image_path_by_name('item', 'Book (Black)')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.timer' }" slot="anchor">
                    <card title="Task & Boss" subtitle="Timer">
                        <img :src="image_path_by_name('item', 'Ancient Watch')" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                    </card>
                </router-link>
            </div>

            <div class="col-md-3">
                <router-link :to="{ name: 'tools.training' }" slot="anchor">
                    <card title="Magic Level" subtitle="Training">
                        <img :src="image_path_by_name('item', 'Spellbook of Ancient Arcana')" slot="icon">
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
                news: []
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
                return moment(date).fromNow()
            }
        },

        methods: {
            getLatestNews () {
                services.getPosts(3)
                    .then(response => {
                        this.news = response.data
                    })
            }
        },

        mounted () {
            this.getLatestNews()
        }
    }
</script>
