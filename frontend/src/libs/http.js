import axios from 'axios'

window.axios = axios

window.axios = axios.create({
    baseURL: 'http://localhost:8888/api/',
    timeout: 3000,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    responseType: 'json'
})