<template>
    <panel class="spot">
        <div class="thumb">
            <img :src="image_path('creature', creature)">
        </div>

        <div class="features">
            <div class="feature">
                <div class="data">
                    <span class="name">Level</span>
                    <span class="value">
                        {{ spot.level_min }}

                        <small>to {{ spot.level_max }}</small>
                    </span>
                </div>
            </div>

            <div class="feature">
                <div class="data">
                    <span class="name">Profit</span>
                    <span class="value">
                        {{ profit }}

                        <small>per hour</small>
                    </span>
                </div>
            </div>
        </div>

        <div class="details">
            <h3 class="title">{{ spot.title }}</h3>
            <h4 class="location">{{ spot.location }}</h4>

            <span class="description">
                {{ description }}
            </span>


            <footer>
                <div class="vocations">
                    <span class="label label-primary" v-for="vocation in spot.vocations" :key="vocation.id">
                        {{ vocation.title }}
                    </span>
                </div>

                <div class="tags">
                    <el-tooltip content="Task Available" placement="top" v-if="spot.has_task">
                        <i class="mdi mdi-format-list-checks"></i>
                    </el-tooltip>

                    <el-tooltip content="Require Premium Account" placement="top" v-if="spot.require_premium">
                        <i class="mdi mdi-crown"></i>
                    </el-tooltip>

                    <el-tooltip content="Require Quest" placement="top" v-if="spot.require_quest">
                        <i class="mdi mdi-treasure-chest"></i>
                    </el-tooltip>

                    <el-tooltip content="Solo Hunting" placement="top" v-if="spot.soloable">
                        <i class="mdi mdi-account"></i>
                    </el-tooltip>

                    <el-tooltip content="Team Hunting" placement="top" v-if="! spot.soloable">
                        <i class="mdi mdi-account-multiple"></i>
                    </el-tooltip>
                </div>
            </footer>
        </div>

        <div class="action">
            <span class="name">Experience</span>
            <span class="value">{{ experience }}</span>
            <span class="period">per hour</span>

            <router-link class="btn btn-sm btn-success" :to="{ name: 'tools.spots.show', params: { id: spot.id } }">
                Read More
            </router-link>
        </div>
    </panel>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import { truncate } from 'lodash'

    export default {
        props: ['spot'],

        computed: {
            creature () {
                return this.spot.creatures && this.spot.creatures.length
                    ? this.spot.creatures[0].id
                    : 0
            },

            experience () {
                return this.spot.experience.format()
            },

            profit () {
                switch (true) {
                    case this.spot.profit > 999999999:
                        return this.spot.profit / 1000000000 + 'kkk'
                    case this.spot.profit > 999999:
                        return this.spot.profit / 1000000 + 'kk'
                    case this.spot.profit > 999:
                        return this.spot.profit / 1000 + 'k'
                    default:
                        return `${this.spot.profit} gp`
                }
            },

            description () {
                const description = this.spot.description.replace(/<(?:.|\n)*?>/gm, '')
                return truncate(description, {
                    'length': 180,
                    'separator': ' '
                })
            }
        }
    }
</script>