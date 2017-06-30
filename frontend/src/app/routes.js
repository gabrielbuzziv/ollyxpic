import Default from 'common/layout/Default'
import { routes as calculators } from 'modules/calculators'
import { routes as pages } from 'modules/pages'

export default [
    {
        path: '/',
        component: Default,
        children: [...calculators, ...pages]
    }
]
