import { vuex as pages } from 'modules/pages'
import { vuex as calculators } from 'modules/calculators'

export default [
    ...calculators,
    ...pages,
]
