import List from './views/List'
import Form from './views/Form'
import Show from './views/Show'

export default [
    {
        path: '/tools/hunting-spots',
        name: 'tools.spots.list',
        component: List,
    },
    {
        path: '/tools/hunting-spots/add',
        name: 'tools.spots.form',
        component: Form,
    },
    {
        path: '/tools/hunting-spots/:id',
        name: 'tools.spots.show',
        component: Show,
    }
]
