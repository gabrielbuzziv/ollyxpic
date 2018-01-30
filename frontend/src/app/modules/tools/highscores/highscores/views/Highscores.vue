<template>
    <page-load id="highscores">
        <page-title>
            <div class="pull-right" :show-timeout="100">
                <el-dropdown>
                    <el-button type="primary">
                        Highscores <i class="mdi mdi-chevron-down margin-left-10"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <router-link :to="{ name: 'tools.highscores.experience' }">
                            <el-dropdown-item>Top Exp</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'tools.highscores.experience', params: { vocation: 'knight' } }">
                            <el-dropdown-item>Top Knight</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'tools.highscores.experience', params: { vocation: 'druid' } }">
                            <el-dropdown-item>Top Druid</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'tools.highscores.experience', params: { vocation: 'sorcerer' } }">
                            <el-dropdown-item>Top Sorcerer</el-dropdown-item>
                        </router-link>

                        <router-link :to="{ name: 'tools.highscores.experience', params: { vocation: 'paladin' } }">
                            <el-dropdown-item>Top Paladin</el-dropdown-item>
                        </router-link>
                    </el-dropdown-menu>
                </el-dropdown>

            </div>

            <img :src="image_path_by_name('item', 'Crown')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Highscores</h2>
                <span>Best Tibia Players</span>
            </div>
        </page-title>

        <router-view></router-view>
    </page-load>
</template>
