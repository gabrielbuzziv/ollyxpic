export default {
    getPost () {
        return window.axios.get(`news`)
    }
}