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
            <b>+ {{ weekAdvances }}</b>
            <span>Last 7 days experience</span>
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
            weekAdvances () {
                return this.player
                    ? this.player.week_experience.reduce((carry, advance) => {
                        return carry + advance.advance
                    }, 0).format()
                    : 0
            }
        }
    }
</script>