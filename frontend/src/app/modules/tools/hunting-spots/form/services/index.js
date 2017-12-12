export default {
    save (data) {
        return window.axios.post(`hunting-spots`, data)
    },

    getHuntingSpots (page, limit, sort, filters) {
        return window.axios.get(`hunting-spots`, { params: { page, limit, sort, filters } })
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