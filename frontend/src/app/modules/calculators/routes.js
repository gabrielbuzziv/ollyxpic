import Calculators from './Calculators'
import { routes as waste } from './waste'
import { routes as hunt } from './hunt'
import { routes as loot } from './loot'
import { routes as bless } from './bless'
import { routes as speedboost } from './speedboost'
import { routes as imbuements } from './imbuements'

export default [
    {
        name: 'calculators.index',
        path: '/calculators',
        component: Calculators
    },

    ...waste,

    ...hunt,

    ...loot,

    ...bless,

    ...speedboost,

    ...imbuements,
]
