import Default from 'common/layout/Default'
import { routes as tools } from 'modules/tools'
import { routes as pages } from 'modules/pages'
import { routes as admin } from 'modules/admin'

export default [
    {
        path: '/',
        component: Default,
        children: [...tools, ...pages, ...admin]
    }
]
