import Default from 'common/layout/Default'
import { routes as calculators } from 'modules/calculators'
import { routes as pages } from 'modules/pages'
import { routes as admin } from 'modules/admin'

export default [
    {
        path: '/',
        component: Default,
        children: [...calculators, ...pages, ...admin]
    }
]
