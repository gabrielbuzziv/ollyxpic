import services from '../services'

export default {
    'spellcast/FETCH_CREATURES' (context) {
        services.getCreatures()
            .then(response => {
                context.commit('spellcast/CREATURES', response.data)
            })
    }
}