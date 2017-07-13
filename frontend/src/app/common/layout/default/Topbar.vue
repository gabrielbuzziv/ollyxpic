<template>
    <div id="topbar">
        <div class="container">
            <div class="pull-left">
                <span class="url">
                    www.ollyxpic.com
                </span>
            </div>

            <div class="pull-right">
                <router-link :to="{ name: 'pages.contact' }">
                    <span class="url">
                        Request a tool
                    </span>
                </router-link>

                <span>|</span>

                <router-link :to="{ name: 'pages.contact' }">
                    <span class="url">
                        Contact Us
                    </span>
                </router-link>
            </div>
        </div>
    </div>
</template>