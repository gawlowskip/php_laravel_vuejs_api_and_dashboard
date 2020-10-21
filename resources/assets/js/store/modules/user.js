import UserService from "../../services/UserService"
import PropertyService from "../../services/PropertyService"

const namespaced = true

const state = {
    users: null,
    usersMeta: null,
    user: null,

    userProperty: null,

    perPage: 5
}

const mutations = {
    SET_USERS_DATA(state, usersData) {
        state.users = usersData.data
        state.usersMeta = usersData.meta ? usersData.meta : null
    },
    SET_USER_DATA(state, userData) {
        state.user = userData
    },
    SET_USER_PROPERTY_DATA(state, userPropertyData) {
        state.userProperty = userPropertyData
    },
    SET_USER_PROPERTY_IMAGE(state, imageData) {
        if (!state.userProperty.images) {
            state.userProperty.images = {}
        }

        (state.userProperty.images).push(imageData)
    },
    REMOVE_USER_PROPERTY_IMAGE(state, imageId) {
        state.userProperty.images = (state.userProperty.images).filter(image => image.id !== imageId)
    },
    SET_USER_PROPERTY_VIDEO(state, videoData) {
        if (!state.userProperty.videos) {
            state.userProperty.videos = {}
        }

        (state.userProperty.videos).push(videoData)
    },
    REMOVE_USER_PROPERTY_VIDEO(state, videoId) {
        state.userProperty.videos = (state.userProperty.videos).filter(video => video.id !== videoId)
    },
}

const actions = {
    fetchUsers({ commit, state }, { page }) {
        return UserService.getUsers(state.perPage, page).then(response => {
            commit('SET_USERS_DATA', response.data)
        })
    },
    fetchUser({ commit }, { id }) {
        return UserService.getUser(id).then(response => {
            commit('SET_USER_DATA', response.data.data)
        })
    },
    fetchUserProperty({ commit }, { propertyId }) {
        return PropertyService.getProperty(propertyId).then(response => {
            commit('SET_USER_PROPERTY_DATA', response.data.data)
        })
    },
}

export default {
    namespaced,
    state,
    mutations,
    actions
}