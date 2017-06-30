import Vue from 'vue'

Vue.mixin({
    data () {
        return {
            baseURL: 'http://localhost:8081'
        }
    },

    filters: {
        capitalize (value) {
            return value.charAt(0).toUpperCase() + value.slice(1)
        },
    },

    methods: {
        item_path (item) {
            return `http://localhost:8888/images/${item}`
        },

        image_path (type, id) {
            return `http://localhost:8888/images/${type}/${id}`
        },

        convertToCrystal (value, ext = false) {
            value = value / 1000
            ext   = ext ? 'k' : ''

            return value % 1 == 0 ? value.formatMoney(0, '.', '.') + ext : value.formatMoney(1, '.', '.') + ext
        }
    }
})