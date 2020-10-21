import DeveloperService from "../../services/DeveloperService";
import UserService from "../../services/UserService";

const namespaced = true

const state = {
    developerAgreements: null,

    developerAd: null,
    developerAds: null,
    developerAdsMeta: null,

    developerProperties: null,
    developerPropertiesMeta: null,
}

const mutations = {
    SET_DEVELOPER_AGREEMENTS_DATA(state, agreementsData) {
        state.developerAgreements = agreementsData
    },
    SET_DEVELOPER_ADS_DATA(state, adsData) {
        state.developerAds = adsData.data
        state.developerAdsMeta = adsData.meta ? adsData.meta : null
    },
    SET_DEVELOPER_AD_DATA(state, adData) {
        state.developerAd = adData
    },
    SET_DEVELOPER_PROPERTIES_DATA(state, propertiesData) {
        state.developerProperties = propertiesData.data
        state.developerPropertiesMeta = propertiesData.meta ? propertiesData.meta : null
    },
}

const actions = {
    fetchDeveloperAgreements({ commit }, { developerId }) {
        return DeveloperService.getDeveloperAgreements(developerId).then(response => {
            commit('SET_DEVELOPER_AGREEMENTS_DATA', response.data.data)
        })
    },
    fetchDeveloperAds({ commit }, { developerId, page }) {
        return DeveloperService.getDeveloperAds(developerId, page).then(response => {
            commit('SET_DEVELOPER_ADS_DATA', response.data)
        })
    },
    fetchDeveloperAd({ commit }, { developerId, adId }) {
        return DeveloperService.getDeveloperAd(developerId, adId).then(response => {
            commit('SET_DEVELOPER_AD_DATA', response.data.data[0])
        })
    },
    fetchDeveloperProperties({ commit }, { developerId, page }) {
        return DeveloperService.getDeveloperProperties(developerId, page).then(response => {
            commit('SET_DEVELOPER_PROPERTIES_DATA', response.data)
        })
    },
}

export default {
    namespaced,
    state,
    mutations,
    actions
}