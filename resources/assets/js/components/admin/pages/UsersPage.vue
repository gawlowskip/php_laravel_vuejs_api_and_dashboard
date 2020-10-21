<template>
    <div class="container">
        <div class="content-title py-4 d-flex justify-content-between align-items-center">
            <div class="btn-toolbar ml-auto">
                <router-link :to="{name: 'UserCreatePage'}" tag="a" class="btn btn-primary">
                    <i class="fas fa-plus"></i>{{ trans.get('create') }}
                </router-link>
            </div>
        </div>

        <div class="developers-list shadow p-3 mb-4 rounded">
            <user-item :user="user" v-for="user in users" :key="user.id"
                       @deleteUserFromList="deleteUserFromList"></user-item>
            <pagination :meta="usersMeta" componentName="UsersPage"></pagination>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import BaseInput from "../base/BaseInput";
    import store from "../../../store/store";
    import UserItem from "../UserItem";
    import Pagination from "../Pagination";
    import AxiosService from "../../../services/AxiosService";

    function fetchUsers(routeTo, next) {
        const currentPage = parseInt(routeTo.query.page) || 1
        store.dispatch('user/fetchUsers', {
            page: currentPage
        }).then(() => {
            routeTo.params.page = currentPage
            next()
        })
    }

    export default {
        name: "UsersPage",
        props: {
            page: {
                type: Number,
                required: true
            }
        },
        components: {Pagination, UserItem, BaseInput},
        beforeRouteEnter(routeTo, routeFrom, next) {
            fetchUsers(routeTo, next)
        },
        beforeRouteUpdate(routeTo, routeFrom, next) {
            fetchUsers(routeTo, next)
        },
        data() {
            return {
                data: {
                    first_name: ''
                },
            }
        },
        methods: {
            deleteUserFromList(id) {
                let data = this.users.filter(user => user.id !== id)
                let meta = null

                this.$store.commit('user/SET_USERS_DATA', AxiosService.rebuildResponse(data, meta))
            },
        },
        computed: {
            ...mapState({
                users: state => state.user.users,
                usersMeta: state => state.user.usersMeta,
                usersLinks: state => state.user.usersLinks
            })
        },
    }
</script>

<style scoped>

</style>