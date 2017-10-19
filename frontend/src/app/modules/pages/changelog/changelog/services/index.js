export default {
    getChanges () {
        return window.axios.get(`change-log`)
    },
}