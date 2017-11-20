import services from '../services'

export default {
    'huntingspots/FETCH_HUNTINGSPOTS' (context) {
        services.getHuntingSpots()
            .then(response => {
                context.commit('huntingspots/HUNTINGSPOTS', response.data)
            })
    },
    'huntingspots/FETCH_CREATURES' (context) {
        services.getCreatures()
            .then(response => {
                context.commit('huntingspots/CREATURES', response.data)
            })
    }
}
