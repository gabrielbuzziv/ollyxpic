export default {
    user: localStorage.getItem('auth_user') || {},
    token: localStorage.getItem('auth_token') || '',
    validation: []
}