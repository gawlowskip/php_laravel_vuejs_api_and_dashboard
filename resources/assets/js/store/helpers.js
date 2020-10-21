import {mapGetters} from "vuex";

export const authComputed = {
    ...mapGetters('auth', ['loggedIn', 'authId', 'authName'])
}