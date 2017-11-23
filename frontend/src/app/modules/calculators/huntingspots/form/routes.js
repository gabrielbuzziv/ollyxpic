import Form from './views/Form'
import Submit from './views/Submit'

export default [
    {
        path: '/calculators/huntingspots',
        name: 'calculators.huntingspots',
        component: Form,
    },
    {
        path: '/calculators/huntingspots/submit',
        name: 'calculators.huntingspots.submit',
        component: Submit,
    }
]
