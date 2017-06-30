import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import authorize from './authorize'

Vue.use(VueRouter)

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active'
})

router.beforeEach(authorize)

export default router