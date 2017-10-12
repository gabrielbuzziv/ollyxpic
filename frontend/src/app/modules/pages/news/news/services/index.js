export default {
    fetchSupplies () {
        return window.axios.get(`items/supplies`)
    }
}