import Index from './views/Index'
import Edit from './views/Edit'

export default [
    {
        path: '/admin/categories',
        name: 'admin.categories',
        component: Index,
        meta: { auth: true }
    },
    {
        path: '/admin/categories/:id',
        name: 'admin.categories.edit',
        component: Edit,
        meta: { auth: true }
    },
]
