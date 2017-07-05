import services from '../services'

export default {
    'waste/FETCH_SUPPLIES' (context) {
        // Potions
        services.fetchPotions()
            .then(response => {
                context.commit('waste/POTIONS', response.data)
            })

        // Ammunitions
        services.fetchAmmunitions()
            .then(response => {
                context.commit('waste/AMMUNITIONS', response.data)
            })

        // Runes
        services.fetchRunes()
            .then(response => {
                context.commit('waste/RUNES', response.data)
            })

        // Amulets
        services.fetchAmulets()
            .then(response => {
                context.commit('waste/AMULETS', response.data)
            })

        // Rings
        services.fetchRings()
            .then(response => {
                context.commit('waste/RINGS', response.data)
            })
    }
}