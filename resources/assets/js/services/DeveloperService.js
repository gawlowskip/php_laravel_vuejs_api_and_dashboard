import axios from 'axios'
import AxiosService from "./AxiosService"

export default {
    getDeveloperAgreements(developerId) {
        /* TODO: Get only active and verified agreements */
        let params = {
            params: {
                developer_id: developerId,
                verified: 1
            }
        }

        AxiosService.setHeaders(axios)
        return axios.get(`/api/agreements`, params)
    },
    updateDeveloperAgreement(developerId, developerAgreementId, agreementData) {
        let data = {...agreementData}

        if (data.stripe_plan) {
            data.stripe_plan = data.stripe_plan.value
        }

        if (data.type) {
            data.type = data.type.value

            if (data.type !== 'trial') {
                data.trial_starts_at = null
                data.trial_ends_at = null
            }
        }
        AxiosService.setHeaders(axios)
        return axios.put(`/api/developers/${developerId}/agreements/${developerAgreementId}`, data)
    },
    destroyDeveloperAgreement(developerId, agreementId) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/developers/${developerId}/agreements/${agreementId}`)
    },
    getDeveloperAds(developerId, page) {
        let params = {
            params: {
                developer_id: developerId,
                page: page
            }
        }

        AxiosService.setHeaders(axios)
        return axios.get(`/api/ads`, params)
    },
    getDeveloperAd(developerId, adId) {
        let params = {
            params: {
                developer_id: developerId,
                id: adId
            }
        }

        AxiosService.setHeaders(axios)
        return axios.get(`/api/ads`, params)
    },
    updateDeveloperAd(developerId, adId, userAdData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...userAdData}

        if (data.active) {
            data.active = data.active.value
        }

        if (data.areas) {
            data.areas = Object.entries(data.areas).map(([key, value]) => value.value)
        }

        let formData = AxiosService.jsonToFormData(data)
        formData.append('_method', 'PUT')

        AxiosService.setHeaders(axios)
        return axios.post(`/api/developers/${developerId}/ads/${adId}`, formData, headers)
    },
    destroyDeveloperAd(developerId, adId) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/developers/${developerId}/ads/${adId}`)
    },
    destroyDeveloperAdImage(developerId, adId) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/developers/${developerId}/ads/${adId}/image`)
    },
    getDeveloperProperties(developerId, page) {
        let params = {
            params: {
                developer_id: developerId,
                page: page
            }
        }

        AxiosService.setHeaders(axios)
        return axios.get(`/api/properties`, params)
    },
}