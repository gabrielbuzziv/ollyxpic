import Index from './views/Index'

export default [
    {
        path: '/admin/syncronizer',
        name: 'admin.syncronizer',
        component: Index,
        meta: { auth: true }
    },
]
