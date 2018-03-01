import { vuex as damageHealing } from './damage-healing'
import { vuex as huntingSpots } from './hunting-spots'
import { vuex as players } from './players'

export default [
    ...damageHealing,
    ...huntingSpots,
    ...players
]
