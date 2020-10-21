import StripeService from "../../services/StripeService";

const namespaced = true

const state = {
    stripePlans: null,
}

const mutations = {
    SET_PLANS_DATA(state, plansData) {
        state.stripePlans = plansData
    },
}

const actions = {
    fetchPlans({commit, state}) {
        return StripeService.getPlans().then(response => {
            commit('SET_PLANS_DATA', response.data.data)
        })
    }
}

export default {
    namespaced,
    state,
    mutations,
    actions
}