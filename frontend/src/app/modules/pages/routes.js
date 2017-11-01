import { routes as home } from './home'
import { routes as contact } from './contact'
import { routes as news } from './news'
import { routes as changelog } from './changelog'
import { routes as partners } from './partners'
import { routes as tibiabosses } from './tibiabosses'



export default [
    ...home,
    ...contact,
	...news,
	...changelog,
	...partners,
	...tibiabosses,
]
