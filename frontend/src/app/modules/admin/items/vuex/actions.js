import services from '../services'

export default {
    'items/FETCH_ITEMS' (context, request) {
        return services.getItemsFromCategory(request)
            .then(response => {
                context.commit('items/ITEMS', response.data)
            })
    },

    'items/FETCH_CATEGORIES' (context) {
        return services.getCategories()
            .then(response => {
                context.commit('items/CATEGORIES', response.data)
            })
    },

    'items/TOGGLE_USABLE' (context, request) {
        return services.toggleUsable(request)
    },

    'items/TOGGLE_SUPPLY' (context, request) {
        return services.toggleSupply(request)
    },

    'items/TOGGLE_EQUIPMENT' (context, request) {
        return services.toggleEquipment(request)
    }
}