<template>
    <page-load class="experience no-padding" :loading="loading">
        <template slot="loading">
            <div class="highscore-loading">
                <div class="loader inline"></div>
                <div class="loading-text">
                    {{ loadingText }}
                    <small>This can take few seconds</small>
                </div>
            </div>
        </template>

        <panel class="highscores">
            <el-table
                    :data="highscoresWithRank"
                    :default-sort="{ prop: 'level', order: 'descending' }">
                <el-table-column prop="name" label="Character" class-name="details" width="300">
                    <template slot-scope="scope">
                        <div class="rank">
                            {{ scope.row.rank }}
                        </div>

                        <div class="name">
                            <router-link :to="{ name: 'players', params: { name: scope.row.name } }">
                                {{ scope.row.name }}
                            </router-link>

                            <span class="vocation">
                                {{ scope.row.vocation }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column :prop="valueProp" label="Skills" class-name="skill" sortable>
                    <template slot-scope="scope">
                        <b>{{ useExperience ? scope.row.experience : scope.row.level }}</b>
                        <span>{{ skillLabel }}</span>
                    </template>
                </el-table-column>

                <el-table-column prop="world_id" class-name="world">
                    <template slot-scope="scope">
                        <router-link :to="{ name: 'highscores.experience', params: { world: scope.row.world.name } }">
                            {{ scope.row.world.name }}
                        </router-link>
                        <span>{{ scope.row.world.type }}</span>
                    </template>
                </el-table-column>
            </el-table>
        </panel>
    </page-load>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    export default {
        props: ['highscores', 'loading'],

        computed: {
            highscoresWithRank () {
                return this.highscores.map((highscore, index) => {
                    return { ...highscore,  rank: index + 1 }
                })
            },

            loadingText () {
                let skill = this.$route.params.skill ? this.$route.params.skill : null
                let world = this.$route.params.world ? this.$route.params.world : 'all worlds'

                switch (this.$route.params.skill) {
                    case 'axe':
                        skill = 'axe fighter'
                        break
                    case 'club':
                        skill = 'club fighter'
                        break
                    case 'sword':
                        skill = 'sword fighter'
                        break
                    case 'distance':
                        skill = 'distance fighter'
                        break
                    case 'shielding':
                        skill = 'defender'
                        break
                }

                return skill
                    ? `Loading the best ${skill} from ${world}`
                    : `Loading the best players from ${world}`
            },

            skill () {
                return this.$route.params.skill
            },

            skillLabel () {
                switch (this.skill) {
                    case 'axe':
                        return 'Axe Fighting'
                    case 'club':
                        return 'Club Fighting'
                    case 'sword':
                        return 'Sword Fighting'
                    case 'distance':
                        return 'Distance Fighting'
                    case 'shielding':
                        return 'Shielding'
                    case 'achievements':
                        return 'Achievements Points'
                    case 'loyalty':
                        return 'Loyalty Points'
                }
            },

            useExperience () {
                return this.skill == 'loyalty' || this.skill == 'achievements' ? true : false
            },

            valueProp () {
                return this.useExperience ? 'experience' : 'level'
            }
        },
    }
</script>