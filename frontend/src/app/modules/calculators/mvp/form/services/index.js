export default {
    calculate (data) {
        return window.axios.post(`mvp`, data)
    },
}