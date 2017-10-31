import services from '../services'

export default {
    'categories/FETCH_CATEGORIES' (context) {
        return services.getCategories()
            .then(response => {
                context.commit('categories/CATEGORIES', response.data)
            })
    },

    'categories/TOGGLE_USABLE' (context, request) {
        return services.toggleUsable(request)
    }
}