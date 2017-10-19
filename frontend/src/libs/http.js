import axios from 'axios'
import jwt_decode from 'jwt-decode'
import store from 'common/vuex'
import { isEmpty } from 'lodash'

window.axios = axios

const baseURL = window.location.href.split('/')[2].includes('ollyxpic') ? 'http://api.ollyxpic.com' : 'http://localhost:8888'

window.axios = axios.create({
    baseURL: `${baseURL}/api`,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    responseType: 'json'
})

window.axios.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token')

    if (! isEmpty(token)) {
        if (jwt_decode(token).exp < (Date.now() / 1000) && ! config.url.includes('/auth/token')) {
            return window.axios.get(`/auth/token`)
                .then(response => {
                    store.commit('global/TOKEN', response.data.token)
                    config.headers.Authorization = `Bearer ${response.data.token}`
                    return config
                })
        }

        config.headers.Authorization = `Bearer ${localStorage.getItem('auth_token')}`
        return config
    }

    return config
}, error => {
    return Promise.reject(error)
})

window.axios.interceptors.response.use(response => {
    return response
}, error => {
    const statusCode = error.response.status

    switch (statusCode) {
        case 422:
            store.dispatch('global/SET_VALIDATION', error.response.data)
        default:
            return Promise.reject(error)
    }

    return Promise.reject(error)
})