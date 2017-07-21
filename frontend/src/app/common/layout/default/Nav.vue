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
                                <img :src="item_path('ultimate-mana-potion')">
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

                    <router-link :to="{ name: 'calculators.speedboost' }" tag="li">
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
                </ul>
            </li>

            <li>
                <a href="#">
                    <span class="left">
                        <img :src="image_path('item', 1291)" alt="">
                    </span>
                    <span class="center">
                        <span>News</span>
                        <small>All about Tibia</small>
                    </span>
                    <span class="label label-primary">
                        Soon
                    </span>
                </a>
            </li>

            <li>
                <a href="#">
                    <span class="left">
                        <img :src="image_path('item', 357)" alt="">
                    </span>
                    <span class="center">
                        <span>Change Log</span>
                        <small>All about OllyxPic</small>
                    </span>
                    <span class="label label-primary">
                        Soon
                    </span>
                </a>
            </li>

            <router-link :to="{ name: 'pages.contact' }" tag="li">
                <a href="#">
                    <span class="left">
                        <img :src="image_path('item', 313)" alt="">
                    </span>
                    <span class="center">
                        <span>Contact Us</span>
                        <small>Send a message</small>
                    </span>
                </a>
            </router-link>
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
            }
        }
    }
</script>
