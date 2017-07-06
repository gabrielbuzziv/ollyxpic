import Calculators from './Calculators'
import { routes as waste } from './waste'
import { routes as teamhunt } from './teamhunt'
import { routes as loot } from './loot'
import { routes as bless } from './bless'

export default [
    {
        name: 'calculators.index',
        path: '/calculators',
        component: Calculators
    },

    ...waste,

    ...teamhunt,

    ...loot,

    ...bless
]
