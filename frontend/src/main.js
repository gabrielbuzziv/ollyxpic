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
  render: h => h(App)
})
