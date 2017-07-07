<template>
    <nav id="nav">
        <ul>
            <router-link class="home" :to="{ name: 'pages.home' }" tag="li" exact>
                <a href="#">
                    <i class="mdi mdi-home"></i>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.waste' }" tag="li">
                <a href="">
                    <img :src="item_path('ultimate-mana-potion')" class="margin-right-5">
                    <span>Waste Calculator</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.teamhunt' }" tag="li">
                <a href="">
                    <img :src="item_path('crystal-coin')" class="margin-right-5">
                    <span>Team Hunt Profit</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.loot' }" tag="li">
                <a href="">
                    <img :src="item_path('magic-plate-armor')" class="margin-right-5">
                    <span>Loot Count</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.blessing' }" tag="li">
                <a href="">
                    <img :src="item_path('spiritual-charm')" class="margin-right-5">
                    <span>Blessings</span>
                    <span class="label label-success">New</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.speedboost' }" tag="li">
                <a href="">
                    <img :src="item_path('boots-of-haste')" class="margin-right-5">
                    <span>Speed Boost</span>
                    <span class="label label-success">New</span>
                </a>
            </router-link>
        </ul>
    </nav>
</template>