export default {
    save (data) {
        return window.axios.post(`hunting-spots`, data)
    },

    getHuntingSpots () {
        return window.axios.get(`hunting-spots`)
    },

    getHuntingSpot (id) {
        return window.axios.get(`hunting-spots/${id}`)
    },

    getVocations () {
        return window.axios.get(`vocations`)
    },

    getSupplies (category) {
        return window.axios.get(`supplies`, { params: { category }})
    },

    getEquipments (category) {
        return window.axios.get(`equipments`, { params: { category }})
    },

    getCreatures (query) {
        return window.axios.get(`creatures`, { params: { query } })
    },
}