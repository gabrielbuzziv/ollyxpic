import services from '../services'

export default {
    'player/FETCH_PLAYER' (context, request) {
        services.getPlayer(request.name)
            .then(response => context.commit('player/PLAYER', response.data))
    },

    'player/FETCH_SKILLS' (context, request) {
        services.getPlayerSkills(request.id)
            .then(response => context.commit('player/SKILLS', response.data))
    },

    'player/FETCH_MONTHS' (context, request) {
        services.getMonths()
            .then(response => context.commit('player/MONTHS', response.data))
    },

    'player/FETCH_EXPERIENCE' (context, request) {
        services.getPlayerExperience(request.id, request.month)
            .then(response => context.commit('player/EXPERIENCE', response.data))
    },
}