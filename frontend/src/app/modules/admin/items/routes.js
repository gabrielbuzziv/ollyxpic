import Index from './views/Index'

export default [
    {
        path: '/admin/items',
        name: 'admin.items',
        redirect: {
            name: 'admin.items.category',
            params: {
                category: 1
            }
        },
        meta: { auth: true }
    },
    {
        path: '/admin/items/:category',
        name: 'admin.items.category',
        component: Index,
        meta: { auth: true }
    },
]
