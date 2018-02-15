import Highscores from './views/Highscores'
import Experience from './views/Experience'
import Skills from './views/Skills'

export default [
    {
        path: '/highscores',
        component: Highscores,
        children: [
            {
                path: '/highscores/experience/:vocation?/:world?',
                name: 'highscores.experience',
                component: Experience
            },
            {
                path: '/highscores/skills/:skill?/:world?',
                name: 'highscores.skills',
                component: Skills
            },
        ]
    }
]
