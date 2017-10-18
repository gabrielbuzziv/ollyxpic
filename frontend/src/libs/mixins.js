import Vue from 'vue'
import { isEmpty, forEach } from 'lodash'

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
        },
    },

    methods: {
        item_path (item) {
            return `${this.baseURL}/images/items/${item}/gif`
        },

        tile_path (item) {
            return `${this.baseURL}/images/tiles/${item}/png`
        },

        image_path (type, id) {
            return `${this.baseURL}/images/blob/${type}/${id}`
        },

        convertToCrystal (value, ext = false) {
            value = value / 1000
            ext   = ext ? 'k' : ''

            return value % 1 == 0 ? value.formatMoney(0, '.', '.') + ext : value.formatMoney(1, '.', '.') + ext
        },

        validation () {
            const validations = this.$store.getters['global/GET_VALIDATION']

            if (! isEmpty(validations)) {
                forEach(validations, validation => {
                    setTimeout(() => {
                        this.$notify.warning({
                            message: validation[0]
                        })
                    }, 1)
                }, '')
            }

            this.$store.dispatch('global/SET_VALIDATION', null)
        }
    }
})