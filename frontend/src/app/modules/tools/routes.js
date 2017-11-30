import Tools from './Tools'
import { routes as lootCount } from './loot-count'
import { routes as bless } from './bless'
import { routes as speedboost } from './speedboost'
import { routes as imbuements } from './imbuements'
import { routes as damageProtection } from './damage-protection'
import { routes as mvp } from './mvp'
import { routes as huntingSpots } from './hunting-spots'
import { routes as damageHealing } from './damage-healing'
import { routes as expshare } from './expshare'
import { routes as capcount } from './capcount'
import { routes as currency } from './currency'

export default [
    {
        name: 'tools.index',
        path: '/tools',
        component: Tools,
        redirect: '/'
    },

    ...lootCount,
    ...bless,
    ...speedboost,
    ...imbuements,
    ...damageProtection,
    ...huntingSpots,
    ...mvp,
    ...damageHealing,
    ...expshare,
    ...capcount,
    ...currency,
]
