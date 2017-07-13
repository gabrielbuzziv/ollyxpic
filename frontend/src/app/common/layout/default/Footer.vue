<template>
    <div id="footer">
        <div class="bar">
            <div class="container">
                <router-link :to="{ name: 'pages.contact' }">
                    Found some bug? Report us! (:
                </router-link>
            </div>
        </div>

        <div class="rights">
            <div class="container">
                <p class="ollyxpic"><a href="http://ollyxpic.com">OllyxPic.com</a> &copy; 2017. All rights reserved.</p>
                <p class="tibia">
                    Please note that the only official website is <a href="http://www.tibia.com" target="_blank">www.tibia.com</a>
                    and from there part of statistical information as well as creature and item graphic info is gathered.
                    Tibia is copyrighted by <a href="https://www.cipsoft.com/" target="_blank">CipSoft GmbH</a>.
                </p>
            </div>
        </div>
    </div>
</template>