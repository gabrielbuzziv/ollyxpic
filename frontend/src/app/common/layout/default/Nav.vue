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
                        <img :src="image_path('item', 1288)" alt="">
                    </span>
                    <span class="center">
                        Calculators
                    </span>
                </a>

                <ul>
                    <router-link :to="{ name: 'calculators.waste' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 3147)">
                            </span>
                            <span class="center">
                                <span>Supplies</span>
                                <small>Waste</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'calculators.imbuements' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 2655)">
                            </span>
                            <span class="center">
                                <span>Imbuements</span>
                                <small>Waste/Time</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'calculators.hunt' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 296)">
                            </span>
                            <span class="center">
                                <span>Hunt</span>
                                <small>Waste/Profit</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'calculators.loot.count' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 93)">
                            </span>
                            <span class="center">
                                <span>Loot</span>
                                <small>Count</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'calculators.loot.acumulator' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 1613)">
                            </span>
                            <span class="center">
                                <span>Loot</span>
                                <small>Acumulator</small>
                            </span>
                        </a>
                    </router-link>

                    <router-link :to="{ name: 'calculators.blessing' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 1922)">
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
                                <img :src="image_path('item', 1)">
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
                                <img :src="image_path('item', 117)" alt="">
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
                                <img :src="image_path('item', 862)">
                            </span>
                            <span class="center">
                                <span>Warzone</span>
                                <small>MVP's</small>
                            </span>
                        </a>
                    </router-link>
					
                    <router-link :to="{ name: 'calculators.damage.protection' }" tag="li">
                        <a href="">
                            <span class="left">
                                <img :src="image_path('item', 208)">
                            </span>
                            <span class="center">
                                <span>Damage</span>
                                <small>Protection</small>
                            </span>
                        </a>
                    </router-link>
                </ul>
            </li>

			<router-link :to="{ name: 'pages.news' }" tag="li">
                <a href="#">
                    <span class="left">
                        <img :src="image_path('item', 1291)" alt="">
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
                        <img :src="image_path('item', 357)" alt="">
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
                        <img :src="image_path('item', 2040)" alt="">
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
                        <img :src="image_path('item', 313)" alt="">
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

            <li>
                <a href="#">
                    <i class="mdi mdi-newspaper margin-right-5"></i>
                    News
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="mdi mdi-tumblr-reblog margin-right-5"></i>
                    Patch Notes
                </a>
            </li>

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
