import List from './views/List'
import Create from './views/Create'
import Edit from './views/Edit'

export default [
    {
        path: '/admin/partners',
        name: 'admin.partners',
        component: List,
        meta: { auth: true }
    },

    {
        path: '/admin/partners/new',
        name: 'admin.partners.new',
        component: Create,
        meta: { auth: true }
    },
    {
        path: '/admin/partners/:id/edit',
        name: 'admin.partners.edit',
        component: Edit,
        meta: { auth: true }
    }
]
