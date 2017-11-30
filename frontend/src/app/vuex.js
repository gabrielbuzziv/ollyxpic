import { vuex as admin } from 'modules/admin'
import { vuex as pages } from 'modules/pages'
import { vuex as tools } from 'modules/tools'

export default [
    ...admin,
    ...tools,
    ...pages,
]
