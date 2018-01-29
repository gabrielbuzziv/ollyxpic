<template>
    <tr>
        <td>
            <b>{{ death.date.date | date }}</b>
            <span>{{ death.date.date | dateForHuman }}</span>
        </td>
        <td>
            <b>{{ death.level }}</b>
            <span>Level</span>
        </td>
        <td>
            <span v-if="creatures.length">
                <img :src="image_path_by_name('creature', creature)" v-for="creature in creatures">
            </span>
        </td>
    </tr>
</template>

<script>
    import services from '../services'

    export default {
        props: ['death'],

        computed: {
            creatures () {
                if (this.death.reason.indexOf('Died by a') !== -1) {
                    let creatures = this.death.reason.split('Died by a ')[1]
                    creatures = creatures.split(', ')

                    return creatures
                }

                return []
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