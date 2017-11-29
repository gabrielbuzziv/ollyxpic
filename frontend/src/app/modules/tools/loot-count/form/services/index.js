export default {
        calculate (data) {
        return window.axios.post(`teamhunt/calculate`, data)
    }
}