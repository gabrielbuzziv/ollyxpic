export default {
    'global/TOKEN' (state, data) {
        localStorage.setItem('auth_token', data)
        state.token = data
    },

    'global/USER' (state, data) {
        localStorage.setItem('auth_user', JSON.stringify(data))
        state.user = data
    },

    'global/VALIDATION' (state, data) {
        state.validation = data
    }
}