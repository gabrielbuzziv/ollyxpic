import List from './views/List'

export default [
    {
        path: '/admin/translations',
        name: 'admin.translations',
        component: List,
        meta: { auth: true }
    },
]
