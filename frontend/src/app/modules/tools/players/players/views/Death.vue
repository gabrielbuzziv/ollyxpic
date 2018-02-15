<template>
    <div class="death">
        <div class="column">
            <img :src="`src/assets/images/${type.image}`" style="max-width: 32px">
        </div>
        <div class="column">
            <b>{{ death.died_at | date }}</b>
            <span>{{ death.died_at | dateForHuman }}</span>
        </div>
        <div class="column">
            <b>{{ death.level }}</b>
            <span>Level</span>
        </div>
        <div class="column reason">
            <div class="pvp" v-if="type.value == 'pvp'">
                Murdered by

                <router-link :to="{ name: 'players', params: { name: murder.name } }"
                             :key="index"
                             v-for="murder, index in murders">
                    {{ murder.name }}<small v-if="index < murders.length - 1">, </small>
                </router-link>
            </div>

            <div class="pve" v-if="type.value == 'pve'">{{ death.reason }}</div>

            <div class="arena" v-if="type.value == 'arena'">
                <b>{{ death.reason }}</b>
                <span>Commited suicide in arena</span>
            </div>
        </div>
    </div>
</template>

<script>
    import services from '../services'

    export default {
        props: ['death'],

        computed: {
            type () {
                if (this.death.involved.length) {
                    return { image: 'death-murder.png', value: 'pvp' }
                }

                if (this.death.reason.indexOf('by energy') !== -1
                    || this.death.reason.indexOf('by earth') !== -1
                    || this.death.reason.indexOf('by death') !== -1) {
                    return { image: 'death-poison.png', value: 'arena' }
                }

                return { image: 'death.png', value: 'pve' }
            },

            murders () {
                return this.death.involved.length
                    ? this.death.involved.filter(murder => murder.name.toLowerCase() != this.$route.params.name)
                    : []
            }
        },

        filters: {
            date (date) {
                return moment(date).format('DD MMM YYYY')
            },

            dateForHuman (date) {
                return moment.tz(date, "YYYY-MM-DD HH:mm:ss", 'Europe/Berlin').fromNow()
            }
        },
    }
</script>