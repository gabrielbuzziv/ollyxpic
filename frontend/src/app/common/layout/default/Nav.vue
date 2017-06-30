<template>
    <nav id="nav">
        <ul>
            <router-link class="home" :to="{ name: 'pages.home' }" tag="li" exact>
                <a href="#">
                    <i class="mdi mdi-home"></i>
                </a>
            </router-link>

            <router-link :to="{ name: 'calculators.index' }" tag="li">
                <a href="">
                    <i class="mdi mdi-calculator"></i>
                    <span>Calculators</span>
                </a>
            </router-link>

            <li>
                <a href="">
                    <i class="mdi mdi-newspaper"></i>
                    <span>News</span>
                </a>
            </li>

            <li>
                <a href="">
                    <i class="mdi mdi-account"></i>
                    <span>Contact Us</span>
                </a>
            </li>
        </ul>
    </nav>
</template>