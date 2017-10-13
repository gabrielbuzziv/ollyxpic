export default {
    sendContact (data) {
        return window.axios.post(`contact`, data)
    }
}