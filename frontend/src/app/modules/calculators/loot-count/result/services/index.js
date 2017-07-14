export default {
    find (id, password) {
        return window.axios.get(`teamhunt/${id}`, { params: { password } })
    },

    updateItem(teamhuntId, item, password) {
        const itemId = item.item_id
        const params = {
            quantity: item.quantity,
            price: item.unit_price,
            password: password
        }
        return window.axios.post(`teamhunt/${teamhuntId}/item/${itemId}`, { params })
    },

    updateTeammate(teamhuntId, teammate, password) {
        const teammateId = teammate.id
        const params = {
            waste: teammate.waste,
            password: password
        }
        return window.axios.post(`teamhunt/${teamhuntId}/teammate/${teammateId}`, { params })
    },

    signPassword (id, password) {
        return window.axios.post(`teamhunt/${id}/sign`, { password })
    }
}