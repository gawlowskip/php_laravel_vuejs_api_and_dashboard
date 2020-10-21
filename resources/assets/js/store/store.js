import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import user from './modules/user'
import developer from "./modules/developer";
import stripe from "./modules/stripe";
import ad from "./modules/ad"

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        auth,
        user,
        developer,
        stripe,
        ad
    },
})