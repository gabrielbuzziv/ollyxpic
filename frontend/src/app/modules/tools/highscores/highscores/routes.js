import Highscores from './views/Highscores'
import Experience from './views/Experience'
import Knight from './views/Knight'

export default [
    {
        path: '/tools/highscores',
        name: 'tools.highscores',
        redirect: { name: 'tools.highscores.experience' },
        component: Highscores,
        children: [
            {
                path: '/tools/highscores/experience',
                name: 'tools.highscores.experience',
                component: Experience
            },
            {
                path: '/tools/highscores/knight',
                name: 'tools.highscores.knight',
                component: Knight
            }
        ]
    }
]
