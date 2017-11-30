import List from './views/List'
import Form from './views/Form'

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
    }
]
