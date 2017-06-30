import Calculators from './Calculators'
import { routes as waste } from './waste'
import { routes as teamhunt } from './teamhunt'

export default [
    {
        name: 'calculators.index',
        path: '/calculators',
        component: Calculators
    },

    ...waste,

    ...teamhunt
]
