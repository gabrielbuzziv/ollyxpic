import List from './views/List'
import Create from './views/Create'
import Edit from './views/Edit'

export default [
    {
        path: '/admin/imbuements',
        name: 'admin.imbuements',
        component: List,
        meta: { auth: true }
    },
    {
        path: '/admin/imbuements/create',
        name: 'admin.imbuements.create',
        component: Create,
        meta: { auth: true }
    },
    {
        path: '/admin/imbuements/:id/edit',
        name: 'admin.imbuements.edit',
        component: Edit,
        meta: { auth: true }
    }
]
