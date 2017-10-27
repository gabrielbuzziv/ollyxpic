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
                        Calculators
                    </span>
                </a>

                <ul>

                    <router-link :to="{ name: 'calculators.imbuements' }" tag="li">
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

                    <router-link :to="{ name: 'calculators.loot.count' }" tag="li">
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

                    <router-link :to="{ name: 'calculators.blessing' }" tag="li">
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

                    <router-link :to="{ name: 'calculators.speedboost' }" tag="li">
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

                    <router-link :to="{ name: 'calculators.spellcaster' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'sudden death rune')" alt="">
                            </span>
                            <span class="center">
                                <span>Spell Caster</span>
                                <small>Damage & Healing</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'calculators.mvp' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path_by_name('item', 'medal of honour')">
                            </span>
                            <span class="center">
                                <span>Warzone</span>
                                <small>MVP's</small>
                            </span>
                        </a>
                    </router-link>

                    <!--<router-link :to="{ name: 'calculators.damage.protection' }" tag="li">-->
                        <!--<a href="">-->
                            <!--<span class="left">-->
                                <!--<img :src="image_path_by_name('item', 'great shield')">-->
                            <!--</span>-->
                            <!--<span class="center">-->
                                <!--<span>Damage</span>-->
                                <!--<small>Protection</small>-->
                            <!--</span>-->
                        <!--</a>-->
                    <!--</router-link>-->
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
                        <img :src="image_path_by_name('item', 'scroll')" alt="">
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
                        <img :src="image_path_by_name('item', 'Cateroide\'s doll')" alt="">
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
            <li class="header">
                <span>Admin Menu</span>
            </li>

            <router-link :to="{ name: 'admin.news.list' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-newspaper margin-right-5"></i>
                    News
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.changes.list' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-tumblr-reblog margin-right-5"></i>
                    Change Logs
                </a>
            </router-link>

            <router-link :to="{ name: 'admin.damage.list' }" tag="li">
                <a href="#">
                    <i class="mdi mdi-tumblr-reblog margin-right-5"></i>
                    Damage Protection
                </a>
            </router-link>

            <li>
                <a href="#">
                    <i class="mdi mdi-face margin-right-5"></i>
                    Partners
                </a>
            </li>
        </ul>
    </nav>
</template>

<script type="text/babel">
    export default {
        computed: {
            isCalculator () {
                if (this.$route.name.includes('calculators')) {
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
