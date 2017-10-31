import Calculators from './Calculators'
import { routes as lootCount } from './loot-count'
import { routes as bless } from './bless'
import { routes as speedboost } from './speedboost'
import { routes as imbuements } from './imbuements'
import { routes as damageProtection } from './damage-protection'
import { routes as mvp } from './mvp'
import { routes as spellcaster } from './spellcaster'
<<<<<<< HEAD
import { routes as expshare } from './expshare'
import { routes as capcount } from './capcount'
=======
import { routes as currency } from './currency'
>>>>>>> structure

export default [
    {
        name: 'calculators.index',
        path: '/calculators',
        component: Calculators,
        redirect: '/'
    },

    ...lootCount,

    ...bless,

    ...speedboost,

    ...imbuements,
    
    ...damageProtection,

    ...mvp,

    ...spellcaster,

    ...expshare,

    ...capcount,

    ...currency,
]
