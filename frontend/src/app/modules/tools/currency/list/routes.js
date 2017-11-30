import List from './views/List'
import World from './views/World'

export default [
    {
        path: '/tools/currencies',
        name: 'tools.currencies',
        component: List,
    },
    {
        path: '/tools/currencies/:id/world',
        name: 'tools.currencies.world',
        component: World,
    }
]
