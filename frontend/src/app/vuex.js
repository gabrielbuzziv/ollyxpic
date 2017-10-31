import { vuex as admin } from 'modules/admin'
import { vuex as pages } from 'modules/pages'
import { vuex as calculators } from 'modules/calculators'

export default [
    ...admin,
    ...calculators,
    ...pages,
]
