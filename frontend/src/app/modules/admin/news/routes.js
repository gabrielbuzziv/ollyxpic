import List from './views/List'
import Create from './views/Create'
import Edit from './views/Edit'

export default [
    {
        path: '/admin/news',
        name: 'admin.news.list',
        component: List,
        meta: { auth: true }
    },
    {
        path: '/admin/news/create',
        name: 'admin.news.create',
        component: Create,
        meta: { auth: true }
    },
    {
        path: '/admin/news/:id/edit',
        name: 'admin.news.edit',
        component: Edit,
        meta: { auth: true }
    }
]
