import Highscores from './views/Highscores'
import Experience from './views/Experience'
import Skills from './views/Skills'

export default [
    {
        path: '/tools/highscores',
        name: 'tools.highscores',
        redirect: { name: 'tools.highscores.experience' },
        component: Highscores,
        children: [
            {
                path: '/tools/highscores/experience/:vocation?/:world?',
                name: 'tools.highscores.experience',
                component: Experience
            },
            {
                path: '/tools/highscores/skills/:skill?/:world?',
                name: 'tools.highscores.skills',
                component: Skills
            },
        ]
    }
]
