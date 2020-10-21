<template>
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">

                <div class="navbar-brand" id="navigationLogo">
                    <a :href="mainUrl" target="_blank">
                        <img :src="logoUrl" class="img-fluid">
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Guest -->
                    <ul class="navbar-nav ml-auto" v-if="!loggedIn">

                        <!--<router-link :to="{name: 'RegisterPage'}" tag="li"  class="nav-link" active-class="active"><a>Register</a></router-link>-->
                        <router-link :to="{name: 'LoginPage'}" tag="li" class="nav-link" active-class="active"><a>Login</a></router-link>
                    </ul>
                    <!-- / Guest -->

                    <!-- Auth -->
                    <ul class="navbar-nav ml-auto" v-else-if="loggedIn">

                        <router-link :to="{name: 'UsersPage'}" tag="li" class="nav-link" active-class="active">
                            <a>Developers</a>
                        </router-link>
                        <router-link :to="{name: 'StatsPage'}" tag="li" class="nav-link" active-class="active">
                            <a>Stats</a>
                        </router-link>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ authName }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#" @click="logout">
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                    <!-- / Auth -->
                </div>

            </nav>
        </div>
    </header>
</template>

<script>
    import {authComputed} from "../../store/helpers";

    export default {
        name: "AdminHeader",
        props: {},
        watch: {},
        data() {
            return {
                mainUrl: '',
                logoUrl: ''
            }
        },
        methods: {
            logout() {
                let data = {
                    reload: true
                }
                this.$store.dispatch('auth/logout', data)
            }
        },
        computed: {
            ...authComputed
        },
        mounted() {
            const element =  document.querySelector('.navbar-brand')

            element.addEventListener("mouseenter", () => {
                this.animateElement('#navigationLogo', 'pulse', {speed: 'faster'})
            }, false);

            this.mainUrl = window.mainUrl
            this.logoUrl = window.adminRouterPrefix + '/img/logo.svg'
        }
    }
</script>

<style scoped>

</style>