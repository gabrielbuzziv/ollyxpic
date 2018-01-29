<template>
    <tr width="100%">
        <td>
            <img src="src/assets/images/skull.png" style="max-width: 32px">
        </td>
        <td>
            <b>{{ death.date.date | date }}</b>
            <span>{{ death.date.date | dateForHuman }}</span>
        </td>
        <td>
            <b>{{ death.level }}</b>
            <span>Level</span>
        </td>
        <td width="50%">
            <span>{{ death.reason }}</span>
        </td>
    </tr>
</template>

<script>
    import services from '../services'

    export default {
        props: ['death'],

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