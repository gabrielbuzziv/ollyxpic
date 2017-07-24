import Vue from 'vue'
import store from 'common/vuex'
import axios from 'axios'

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

window.axios.interceptors.response.use(response => {
    return response
}, error => {
    const statusCode = error.response.status

    switch (statusCode) {
        case 422:
            return Promise.reject({
                status: 422,
                type: 'validation',
                data: error.response.data
            })
        default:
            return Promise.reject({
                status: statusCode,
                data: error.response.data
            })
    }

    return Promise.reject(error)
})