import List from './views/List'
import World from './views/World'

export default [
    {
        path: '/calculators/currencies',
        name: 'calculators.currencies',
        component: List,
    },
    {
        path: '/calculators/currencies/:id/world',
        name: 'calculators.currencies.world',
        component: World,
    }
]
