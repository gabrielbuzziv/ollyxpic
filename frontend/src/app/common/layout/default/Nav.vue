<template>
    <nav id="nav">
        <ul>
            <router-link class="home" :to="{ name: 'pages.home' }" tag="li" exact>
                <a href="#">
                    <i class="mdi mdi-home"></i>
                </a>
            </router-link>

            <li class="with-child" :class="{ 'active': isCalculator }">
                <a href="javascript:void(0)">
                    <span class="left">
                        <img :src="image_path_by_name('item', 'abacus')" alt="">
                    </span>
                    <span class="center">
                        Tools
                    </span>
                </a>

                <ul>

                    <router-link :to="{ name: 'tools.imbuements' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'silencer claws')">
                            </span>
                            <span class="center">
                                <span>Imbuements</span>
                                <small>Waste/Time</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.loot.count' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'steel boots')">
                            </span>
                            <span class="center">
                                <span>Loot</span>
                                <small>Count</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.blessing' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'spiritual charm')">
                            </span>
                            <span class="center">
                                <span>Blessings</span>
                                <small>Penalty</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.speedboost' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'boots of haste')">
                            </span>
                            <span class="center">
                                <span>Speed</span>
                                <small>Boost</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.damage.healing' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'avalanche rune')" alt="">
                            </span>
                            <span class="center">
                                <span>Damage & Healing</span>
                                <small>Spells and Weapons</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.expshare' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'purple tome')">
                            </span>
                            <span class="center">
                                <span>Exp</span>
                                <small>Share</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.capcount' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'blossom bag')">
                            </span>
                            <span class="center">
                                <span>Capacity</span>
                                <small>Count</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.damage.protection' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'great shield')">
                            </span>
                            <span class="center">
                                <span>Damage</span>
                                <small>Protection</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.currencies' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'tibia coins')">
                            </span>
                            <span class="center">
                                <span>Tibia Currency</span>
                                <small>Stock Exchange</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.timer' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'Ancient Watch')">
                            </span>
                            <span class="center">
                                <span>Task & Boss</span>
                                <small>Timer</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.highscores' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'Medal of Honour')">
                            </span>
                            <span class="center">
                                <span>Highscores</span>
                                <small>Best Tibia Players</small>
                            </span>
                        </a>
                    </router-link>

                    <!--<router-link :to="{ name: 'tools.spots.list' }" tag="li">-->
                        <!--<a href="">-->
                            <!--<span class="left">-->
                                <!--<img :src="image_path_by_name('item', 'Map (Brown)')">-->
                            <!--</span>-->
                            <!--<span class="center">-->
                                <!--<span>Hunting</span>-->
                                <!--<small>Spots</small>-->
                            <!--</span>-->
                        <!--</a>-->
                    <!--</router-link>-->

                    <router-link :to="{ name: 'tools.blacklist' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'Book (Black)')">
                            </span>
                            <span class="center">
                                <span>Blacklist</span>
                                <small>Quick Loot</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'tools.training' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'Spellbook of Ancient Arcana')">
                            </span>
                            <span class="center">
                                <span>Magic Level</span>
                                <small>Training</small>
                            </span>
                        </a>
                    </router-link>
                </ul>
            </li>

            <router-link :to="{ name: 'pages.news' }" tag="li">
                <a href="#">
                    <span class="left">
                        <img :src="image_path_by_name('item', 'golden newspaper')" alt="">
                    </span>
                    <span class="center">
                        <span>News</span>
                        <small>All about Tibia</small>
                    </span>
                </a>
            </router-link>

            <router-link :to="{ name: 'pages.changelog' }" tag="li">
                <a href="#">
                    <span class="left">
                        <img :src="image_path_by_name('item', 'scroll of heroic deeds')" alt="">
                    </span>
                    <span class="center">
                        <span>Change Log</span>
                        <small>All about OllyxPic</small>
                    </span>
                </a>
            </router-link>

            <router-link :to="{ name: 'pages.partners' }" tag="li">
                <a href="#">
                    <span class="left">
                        <img :src="image_path_by_name('item', 'Cateroide\'s Doll (Activated)')" alt="">
                    </span>
                    <span class="center">
                        <span>Partners</span>
                        <small>Streamers etc</small>
                    </span>
                </a>
            </router-link>

            <router-link :to="{ name: 'pages.contact' }" tag="li">
                <a href="#">
                    <span class="left">
                        <img :src="image_path_by_name('item', 'letter')" alt="">
                    </span>
                    <span class="center">
                        <span>Contact Us</span>
                        <small>Send us a message</small>
                    </span>
                </a>
            </router-link>
        </ul>

        <ul class="admin-menu" v-if="isLogged">
            <router-link :to="{ name: 'admin.news.list' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-newspaper margin-right-5"></i>
                    News
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.changes.list' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-magnify margin-right-5"></i>
                    Change Logs
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.categories' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-tag margin-right-5"></i>
                    Categories
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.items' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-view-list margin-right-5"></i>
                    Items
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.imbuements' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-washing-machine margin-right-5"></i>
                    Imbuements
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.worlds' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-earth margin-right-5"></i>
                    Worlds
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.syncronizer' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-database margin-right-5"></i>
                    Database Sync
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.translations' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-web margin-right-5"></i>
                    Translations
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.partners' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-account-multiple margin-right-5"></i>
                    Partners
                </a>
            </router-link>
        </ul>
    </nav>
</template>

<script type="text/babel">
    export default {
        computed: {
            isCalculator () {
                if (this.$route.name.includes('tools')) {
                    return true
                }

                return false
            },

            isLogged () {
                return this.$store.getters['global/IS_LOGGED']
            }
        }
    }
</script>
