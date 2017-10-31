export default {
    'global/ON_LOGIN' (context, request) {
        const credentials = {
            email: request.email,
            password: request.password
        }

        return window.axios.post(`/auth`, credentials)
            .then(response => {
                context.commit('global/TOKEN', response.data.token)
            })
    },

    'global/FETCH_USER' (context) {
        return window.axios.post(`/admin/auth/user`)
            .then(response => {
                context.commit('global/TOKEN', response.data.token)
                context.commit('global/USER', response.data.user)

                return response
            })
    },

    'global/ON_LOGOUT' (context, request) {
    },

    'global/SET_VALIDATION' (context, request) {
        context.commit('global/VALIDATION', request)
    },
}