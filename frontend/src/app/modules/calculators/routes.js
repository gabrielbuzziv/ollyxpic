import Calculators from './Calculators'
import { routes as waste } from './waste'
import { routes as hunt } from './hunt'
import { routes as lootCount } from './loot-count'
import { routes as lootAcumulator } from './loot-acumulator'
import { routes as bless } from './bless'
import { routes as speedboost } from './speedboost'
import { routes as imbuements } from './imbuements'
import { routes as damageProtection } from './damage-protection'
import { routes as mvp } from './mvp'
import { routes as spellcaster } from './spellcaster'

export default [
    {
        name: 'calculators.index',
        path: '/calculators',
        component: Calculators,
        redirect: '/'
    },

    ...waste,

    ...hunt,

    ...lootCount,
    
    ...lootAcumulator,

    ...bless,

    ...speedboost,

    ...imbuements,
    
    ...damageProtection,

    ...mvp,

    ...spellcaster,

]
