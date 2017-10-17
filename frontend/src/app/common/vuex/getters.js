import { isEmpty } from 'lodash'

export default {
    'global/GET_USER' ({ user }) {
        let isJSON = true

        try { JSON.parse(user) } catch (e) { isJSON = false }

        if (isJSON) {
            user = JSON.parse(user)
        }

        return user
    },

    'global/GET_TOKEN' ({ token }) {
        return token
    },

    'global/IS_LOGGED' ({ token }) {
        return ! isEmpty(token) ? true : false
    },

    'global/GET_VALIDATION' ({ validation }) {
        return validation
    }
}