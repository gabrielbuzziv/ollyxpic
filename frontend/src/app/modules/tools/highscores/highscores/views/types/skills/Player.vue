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
            <span>{{ skill }}</span>
        </td>
        <td class="world">
            <b>{{ player.world.name }}</b>
            <span>{{ player.world.type }}</span>
        </td>
        <td class="advances">
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

    export default {
        props: ['player', 'index'],

        computed: {
            skill () {
                switch (this.$route.params.skill) {
                    case 'magic':
                        return 'Magic Level'
                    case 'axe':
                        return 'Sword Fighting'
                    case 'club':
                        return 'Club Fighting'
                    case 'sword':
                        return 'Sword Fighting'
                    case 'distance':
                        return 'Distance Fighting'
                    case 'shielding':
                        return 'Shielding'
                }
            }
        }
    }
</script>