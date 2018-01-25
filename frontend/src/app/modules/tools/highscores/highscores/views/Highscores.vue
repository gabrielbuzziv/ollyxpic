<template>
    <page-load id="highscores">
        <page-title>
            <img :src="image_path_by_name('item', 'Crown')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Highscores</h2>
                <span>Best Tibia Players</span>
            </div>
        </page-title>

        <ul class="menu">
            <li>
                <router-link :to="{ name: 'tools.highscores.experience' }">
                    Top Exp
                </router-link>
            </li>

            <li>
                <router-link :to="{ name: 'tools.highscores.knight' }">
                    Top Knight
                </router-link>
            </li>
        </ul>

        <router-view></router-view>
    </page-load>
</template>
