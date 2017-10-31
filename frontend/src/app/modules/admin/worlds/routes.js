import List from './views/List'
import Create from './views/Create'
import Edit from './views/Edit'

export default [
    {
        path: '/admin/worlds',
        name: 'admin.worlds',
        component: List,
        meta: { auth: true }
    },
    {
        path: '/admin/worlds/create',
        name: 'admin.worlds.create',
        component: Create,
        meta: { auth: true }
    },
    {
        path: '/admin/worlds/:id/edit',
        name: 'admin.worlds.edit',
        component: Edit,
        meta: { auth: true }
    }
]
