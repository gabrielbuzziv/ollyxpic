import Compare from './views/Compare'

export default [
    {
        path: '/compare/players/:first/:second?/:third?/:fourth?',
        name: 'compare.players',
        component: Compare,
    }
]
