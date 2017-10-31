import Index from './views/Index'

export default [
    {
        path: '/admin/items',
        name: 'admin.items',
        component: Index,
        meta: { auth: true }
    },
]
