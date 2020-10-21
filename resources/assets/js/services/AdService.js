import axios from 'axios'
import AxiosService from "./AxiosService";

export default {
    getAdLeads(adId, page) {
        let params = {
            params: {
                page: page
            }
        }

        AxiosService.setHeaders(axios)
        return axios.get(`/api/ads/${adId}/leads`, params)
    },
    storeAd(adData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...adData}

        if (data.active) {
            data.active = data.active.value
        }

        if (data.areas) {
            data.areas = Object.entries(data.areas).map(([key, value]) => value.value)
        }

        if (!data.image) {
            delete data.image
        }

        let formData = AxiosService.jsonToFormData(data)

        AxiosService.setHeaders(axios)
        return axios.post(`/api/ads`, formData, headers)
    },
}