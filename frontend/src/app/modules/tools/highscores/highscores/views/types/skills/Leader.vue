<template>
    <panel class="leader">
        <header>

            <div class="title">
                <div class="rank-badge">
                    {{ index + 1 }}
                </div>

                <span class="left">
                    <span class="name">
                        <router-link :to="{ name: 'players', params: { name: leader.name } }">
                            {{ leader.name }}
                        </router-link>
                    </span>
                    <span class="vocation">{{ leader.vocation }}</span>
                </span>
            </div>
        </header>

        <div class="world">
            <b>{{ leader.world.name }}</b>
            <span>{{ leader.world.type }}</span>
        </div>

        <div class="skills">
            <div>
                <b>{{ leader.level.format() }}</b>
                <span>{{ skill }}</span>
            </div>
        </div>

        <router-link :to="{ name: 'players', params: { name: leader.name } }" class="btn btn-xs btn-rounded margin-top-20">
            <i class="mdi mdi-chart-bar margin-right-10"></i>
            Statistics
        </router-link>
    </panel>
</template>

<script>
    export default {
        props: ['leader', 'index'],

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