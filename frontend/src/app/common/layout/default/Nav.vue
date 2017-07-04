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
                    <i class="mdi mdi-calculator"></i>
                    <span>Waste Calculator</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.teamhunt' }" tag="li">
                <a href="">
                    <i class="mdi mdi-calculator"></i>
                    <span>Team Hunt Profit</span>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.loot' }" tag="li">
                <a href="">
                    <i class="mdi mdi-calculator"></i>
                    <span>Loot Count</span>
                </a>
            </router-link>
        </ul>
    </nav>
</template>