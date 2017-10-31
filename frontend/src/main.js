import Vue from 'vue'
import router from 'router'
import store from 'common/vuex'
import './libs'
import 'common/components'


import App from './App.vue'

window.Vue = Vue

new Vue({
    router,

    store,

    el: '#app',

    render: h => h(App),

    computed: {
        user () {
            return this.$store.getters['global/GET_USER']
        }
    },

    mounted () {
        if (this.$route.meta.auth) {
            this.$store.dispatch('global/FETCH_USER')
                .catch(() => {
                    this.$store.commit('global/TOKEN', '')
                    this.$store.commit('global/USER', '')

                    localStorage.clear()
                    this.$router.push('/')
                })
        }
    }
})
