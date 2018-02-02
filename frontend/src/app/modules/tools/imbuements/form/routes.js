import Form from './views/Form'
import Index from './views/Index'

export default [
    {
        path: '/tools/imbuements',
        name: 'tools.imbuements',
        component: Form,
    },
    {
        path: '/tools/imbuements/test',
        name: 'tools.imbuements.test',
        component: Index,
    }
]
