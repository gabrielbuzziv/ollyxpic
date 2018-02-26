import { createActionHelpers } from 'vuex-loading'
const { startLoading, endLoading } = createActionHelpers({ moduleName: 'loading' });
import services from '../services'

export default {
    'player/FETCH_PLAYER' (context, request) {
        context.commit('player/PLAYER', {})
        context.commit('player/SKILLS', [])
        context.commit('player/MONTHS', [])
        context.commit('player/EXPERIENCE', [])

        return new Promise ((resolve, reject) => {
            services.getPlayer(request.name)
                .then(response => {
                    context.commit('player/PLAYER', response.data)
                    context.dispatch('player/FETCH_LEVEL', { id: context.state.player.id })
                    context.dispatch('player/FETCH_SKILLS', { id: context.state.player.id })
                    context.dispatch('player/FETCH_MONTHS')
                    resolve()
                })
                .catch(() => reject())
        })
    },

    'player/FETCH_LEVEL' (context, request) {
        services.getPlayerLevel(request.id)
            .then(response => context.commit('player/LEVEL', response.data))
    },

    'player/FETCH_SKILLS' (context, request) {
        services.getPlayerSkills(request.id)
            .then(response => context.commit('player/SKILLS', response.data))
    },

    'player/FETCH_MONTHS' (context) {
        return services.getMonths()
            .then(response => {
                context.commit('player/MONTHS', response.data)
                context.dispatch('player/FETCH_EXPERIENCE', {
                    id: context.state.player.id,
                    month: context.state.months[0]
                })
            })
    },

    'player/FETCH_EXPERIENCE' (context, request) {
        context.state.experience = []

        return new Promise((resolve, reject) => {
            services.getPlayerExperience(request.id, request.month)
                .then(response => {
                    context.commit('player/EXPERIENCE', response.data)
                    resolve()
                })
                .catch(() => reject())
        })
    },
}