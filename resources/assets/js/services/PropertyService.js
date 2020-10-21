import axios from 'axios'
import AxiosService from "./AxiosService";

export default {
    getProperty(propertyId) {
        AxiosService.setHeaders(axios)
        return axios.get(`/api/properties/${propertyId}`)
    },
    storeProperty(propertyData) {
        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties`, propertyData)
    },
    updateProperty(propertyId, propertyData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...propertyData}

        let formData = AxiosService.jsonToFormData(data)
        formData.append('_method', 'PUT')

        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}`, formData, headers)
    },
    destroyProperty(propertyId) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/properties/${propertyId}`)
    },
    storePropertyFeatures(propertyId, propertyFeaturesData) {
        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}/features`, propertyFeaturesData)
    },
    updatePropertyFeatures(propertyId, featureId, propertyFeaturesData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...propertyFeaturesData}

        let formData = AxiosService.jsonToFormData(data)
        formData.append('_method', 'PUT')

        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}/features/${featureId}`, formData, headers)
    },
    storePropertyImages(propertyId, propertyImagesData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...propertyImagesData}

        let formData = AxiosService.jsonToFormData(data)

        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}/images`, formData, headers)
    },
    destroyPropertyImage(propertyId, imageId) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/properties/${propertyId}/images/${imageId}`)
    },
    storePropertyVideos(propertyId, propertyVideosData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...propertyVideosData}

        let formData = AxiosService.jsonToFormData(data)

        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}/videos`, formData, headers)
    },
    destroyPropertyVideo(propertyId, videoId) {
        AxiosService.setHeaders(axios)
        return axios.delete(`/api/properties/${propertyId}/videos/${videoId}`)
    },
    storePropertyLocation(propertyId, propertyLocationData) {
        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}/locations`, propertyLocationData)
    },
    updatePropertyLocation(propertyId, locationId, propertyLocationData) {
        let headers = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let data = {...propertyLocationData}

        let formData = AxiosService.jsonToFormData(data)
        formData.append('_method', 'PUT')

        AxiosService.setHeaders(axios)
        return axios.post(`/api/properties/${propertyId}/locations/${locationId}`, formData, headers)
    },
}