export default {
    getHuntingSpots (query) {
        return window.axios.get('huntingspots', { params: { query } })
    },
    getHuntingSpot (id) {
        return window.axios.get(`huntingspots/${id}`)
    }
}