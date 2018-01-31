<template>
    <tr class="player">
        <td class="rank">
            <div class="rank-badge">
                {{ index + 4 }}
            </div>
        </td>
        <td class="details">
            <span class="name">
                <router-link :to="{ name: 'tools.players', params: { name: player.name } }">
                    {{ player.name }}
                </router-link>
            </span>
            <span class="vocation">{{ player.vocation }}</span>
        </td>
        <td class="level">
            <b>{{ player.level }}</b>
            <span>Level</span>
        </td>
        <td class="experience">
            <b>{{ player.experience.format() }}</b>
            <span>Experience</span>
        </td>
        <td class="world">
            <b>{{ player.world.name }}</b>
            <span>{{ player.world.type }}</span>
        </td>
        <td class="advances">
            <div v-if="weekAdvances >= 0">
                <b>+{{ weekAdvances.format() }}</b>
                <span>Last 7 days experience</span>
            </div>

            <div v-else>
                <b class="loose">{{ weekAdvances.format() }}</b>
                <span>Last 7 days experience</span>
            </div>
        </td>
        <td class="text-right">
            <router-link :to="{ name: 'tools.players', params: { name: player.name } }" class="btn btn-sm btn-rounded">
                <i class="mdi mdi-chart-bar margin-right-10"></i>
                Statistics
            </router-link>
        </td>
    </tr>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    import Types from './Types'
    import services from '../services'

    export default {
        props: ['player', 'index'],

        computed: {
            advances () {
                return this.player && this.player.week_experience
                    ? this.player.week_experience.slice().sort((a, b) => a.id - b.id).map((experience, index) => {
                        const advance = index > 0
                            ? experience.experience - this.player.week_experience[index - 1].experience
                            : 0

                        return { ...experience, ...{ advance } }
                    })
                    : []
            },

            weekAdvances () {
                return this.player
                    ? this.advances.reduce((carry, advance) => {
                        return carry + advance.advance
                    }, 0)
                    : 0
            }
        }
    }
</script>