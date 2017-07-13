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
                        <img :src="item_path('slayer-of-mayhem-overcharged')">
                    </span>
                    <span class="center">
                        <span>Imbuements</span>
                        <small>Waste/Time</small>
                    </span>
                    <span class="label label-success">New</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.hunt' }" tag="li">
                <a href="">
                    <span class="left">
                        <img :src="item_path('crystal-coin')">
                    </span>
                    <span class="center">
                        <span>Hunt</span>
                        <small>Waste/Profit</small>
                    </span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.loot' }" tag="li">
                <a href="">
                    <span class="left">
                        <img :src="item_path('magic-plate-armor')">
                    </span>
                    <span class="center">
                        <span>Loot</span>
                        <small>Counter</small>
                    </span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.blessing' }" tag="li">
                <a href="">
                    <span class="left">
                        <img :src="item_path('spiritual-charm')">
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
                        <img :src="item_path('boots-of-haste')">
                    </span>
                    <span class="center">
                        <span>Speed</span>
                        <small>Boost</small>
                    </span>
                </a>
            </router-link>
        </ul>
    </nav>
</template>