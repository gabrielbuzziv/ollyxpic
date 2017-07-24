export default {
    'global/SET_VALIDATION_ERROR' (context, request) {
        context.commit('global/VALIDATION_ERROR', request)
    },
}