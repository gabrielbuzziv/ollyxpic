import Vue from 'vue'

Vue.mixin({
    filters: {
        capitalize (value) {
            return value.charAt(0).toUpperCase() + value.slice(1)
        },
    },

    computed: {
        baseURL () {
            if (window.location.href.split('/')[2].includes('ollyxpic')) {
                return 'http://api.ollyxpic.com'
            }

            return 'http://localhost:8888';
        }
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
    },

    mounted () {
        console.log(this.baseURL)
    }
})