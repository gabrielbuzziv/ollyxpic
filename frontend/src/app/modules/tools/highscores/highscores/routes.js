import Highscores from './views/Highscores'
import Experience from './views/Experience'

export default [
    {
        path: '/tools/highscores',
        name: 'tools.highscores',
        redirect: { name: 'tools.highscores.experience' },
        component: Highscores,
        children: [
            {
                path: '/tools/highscores/experience/:vocation?',
                name: 'tools.highscores.experience',
                component: Experience
            },
        ]
    }
]
