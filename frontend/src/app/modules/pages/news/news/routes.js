import Index from './views/Index'

export default [
    {
        path: '/news/:slug?',
        name: 'pages.news',
        component: Index,
    }
]
