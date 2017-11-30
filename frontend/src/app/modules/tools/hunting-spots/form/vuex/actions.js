import services from '../services'

export default {
    'spots/FETCH_VOCATIONS' (context) {
        services.getVocations()
            .then(response => {
                context.commit('spots/VOCATIONS', response.data)
            })
    },

    'spots/FETCH_SUPPLIES' (context) {
        services.getSupplies()
            .then(response => {
                context.commit('spots/SUPPLIES', response.data)
            })
    },

    'spots/FETCH_CREATURES' (context, request) {
        if (request != null && request != '') {
            services.getCreatures(request)
                .then(response => {
                    context.commit('spots/CREATURES', response.data)
                })
        } else {
            context.commit('spots/CREATURES', [])
        }
    }
}