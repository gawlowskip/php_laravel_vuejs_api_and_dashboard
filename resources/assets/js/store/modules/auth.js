import axios from "axios";

const namespaced = true

const state = {
    user: null,
}

const mutations = {
    SET_AUTH_USER_DATA(state, userData) {
        state.user = userData
        localStorage.setItem('user', JSON.stringify(userData))
        axios.defaults.headers.common['Authorization'] = `Bearer ${userData.meta.token}`
    },
    CLEAR_AUTH_USER_DATA(state, data) {
        localStorage.removeItem('user')

        if (data.reload) {
            location.reload()
        }
    },
}

const actions = {
    register({commit}, credentials) {
        return axios.post('/api/register', credentials)
            .then(({data}) => {
                commit('SET_AUTH_USER_DATA', data)
            })
    },
    login({commit}, credentials) {
        return axios.post('/api/login', credentials)
            .then(({data}) => {
                commit('SET_AUTH_USER_DATA', data)
            })
    },
    adminLogin({commit}, credentials) {
        return axios.post('/api/admin/login', credentials)
            .then(({data}) => {
                commit('SET_AUTH_USER_DATA', data)
            })
    },
    logout({commit}, data) {
        commit('CLEAR_AUTH_USER_DATA', data)
    },
}

const getters = {
    loggedIn(state) {
        return !!state.user
    },
    authId(state) {
        return !!state.user ? state.user.data.id : null
    },
    authName(state) {
        return !!state.user ? `${state.user.data.name} ${state.user.data.last_name}` : ''
    }
}

export default {
    namespaced,
    state,
    mutations,
    actions,
    getters
}