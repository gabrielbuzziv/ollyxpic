import List from './views/List'
import Create from './views/Create'
import Edit from './views/Edit'

export default [
    {
        path: '/admin/changes',
        name: 'admin.changes.list',
        component: List,
    },
    {
        path: '/admin/changes/create',
        name: 'admin.changes.create',
        component: Create,
    },
    {
        path: '/admin/changes/:id/edit',
        name: 'admin.changes.edit',
        component: Edit,
    }
]
