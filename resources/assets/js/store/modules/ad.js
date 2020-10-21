import AreaService from "../../services/AreaService";
import AdService from "../../services/AdService";

const namespaced = true

const state = {
    areas: null,

    ad: null,

    adLeads: null,
    adLeadsMeta: null
}

const mutations = {
    SET_AD_DATA(state, adData) {
        state.ad = adData
    },
    SET_AREAS_DATA(state, areasData) {
        state.areas = areasData
    },
    SET_AD_LEADS_DATA(state, adLeadsData) {
        state.adLeads = adLeadsData.data
        state.adLeadsMeta = adLeadsData.meta ? adLeadsData.meta : null
    }
}

const actions = {
    fetchAreas({commit, state}) {
        return AreaService.getAreas().then(response => {
            commit('SET_AREAS_DATA', response.data.data)
        })
    },
    fetchAdLeads({commit}, { adId, page }) {
        return AdService.getAdLeads(adId, page).then(response => {
            commit('SET_AD_LEADS_DATA', response.data)
        })
    },
}

export default {
    namespaced,
    state,
    mutations,
    actions
}