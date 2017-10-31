export default {
    syncNPCs () {
        return window.axios.post(`admin/npcs/sync`)
    },

    syncCategories () {
        return window.axios.post(`admin/categories/sync`)
    },

    syncItems () {
        return window.axios.post(`admin/items/sync`)
    },

    syncCreatures () {
        return window.axios.post(`admin/creatures/sync`)
    },

    syncTiles () {
        return window.axios.post(`admin/tiles/sync`)
    }
}