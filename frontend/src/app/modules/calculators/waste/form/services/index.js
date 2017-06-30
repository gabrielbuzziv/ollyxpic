export default {
    fetchPotions () {
        return window.axios.get(`items/potions`)
    },

    fetchAmmunitions () {
        return window.axios.get(`items/ammunitions`)
    },

    fetchRunes () {
        return window.axios.get(`items/runes`)
    },

    fetchAmulets () {
        return window.axios.get(`items/amulets`)
    },

    fetchRings () {
        return window.axios.get(`items/rings`)
    },
    
    calculate (data) {
        return window.axios.post(`waste/calculate`, data)
    }
}