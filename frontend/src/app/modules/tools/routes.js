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
import { routes as blacklist } from './blacklist'
import { routes as timer } from './timer'
import { routes as highscores } from './highscores'
import { routes as players } from './players'
import { routes as playersCompare } from './players-compare'
import { routes as skillTraining } from './skill-training'
import { routes as hardcorePvp } from './hardcore-pvp'

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
    ...mvp,
    ...damageHealing,
    ...huntingSpots,
    ...expshare,
    ...capcount,
    ...currency,
    ...blacklist,
    ...timer,
    ...highscores,
    ...players,
    ...playersCompare,
    ...skillTraining,
    ...hardcorePvp
]
