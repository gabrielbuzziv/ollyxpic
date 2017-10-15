import { routes as home } from './home'
import { routes as contact } from './contact'
import { routes as news } from './news'
import { routes as changelog } from './changelog'
import { routes as partners } from './partners'

export default [
    ...home,
    ...contact,
	...news,
	...changelog,
	...partners,
]
