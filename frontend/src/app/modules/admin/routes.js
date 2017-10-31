import { routes as news } from './news'
import { routes as changes } from './changes'
import { routes as items } from './items'
import { routes as syncronizer } from './syncronizer'
import { routes as categories } from './categories'
import { routes as imbuements } from './imbuements'

export default [
    ...news,
    ...changes,
    ...items,
    ...syncronizer,
    ...categories,
    ...imbuements,
]
