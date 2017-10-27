import { routes as news } from './news'
import { routes as changes } from './changes'
import { routes as damageProtection } from './damage-protection'

export default [
    ...news,
    ...changes,
    ...damageProtection
]
