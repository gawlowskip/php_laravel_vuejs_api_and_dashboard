import axios from 'axios'
import AxiosService from "./AxiosService";

export default {
    getUsers(perPage, page) {
        AxiosService.setHeaders(axios)
        return axios.get(`/api/users?page=${page}`)
    },
    getUser(id) {
        AxiosService.setHeaders(axios)
        return axios.get(`/api/users/${id}`)
    },
    storeUser(userData) {
        let data = {...userData}

        if (data.active) {
            data.active = data.active.value
        }

        AxiosService.setHeaders(axios)
        return axios.post(`/api/users`, data)
    },
    updateUser(userData, userId) {
        let data = {...userData}

        if (data.active) {
            data.active = data.active.value
        }

        AxiosService.setHeaders(axios)
        return axios.put(`/api/users/${userId}`, data)
    },
    destroyUser(id) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/users/${id}`)
    },
    storeUserAgreement(userId, userAgreementData) {
        let data = {...userAgreementData}

        data.user_id = userId

        if (data.stripe_plan) {
            data.stripe_plan_id = data.stripe_plan.value
        }

        if (data.type) {
            data.type = data.type.value

            if (data.type !== 'trial') {
                data.trial_starts_at = null
                data.trial_ends_at = null
            }
        }

        /* Set token for test payments */
        data.token = 'tok_visa'

        AxiosService.setHeaders(axios)
        return axios.post(`/api/stripe/subscriptions`, data)
    },
}