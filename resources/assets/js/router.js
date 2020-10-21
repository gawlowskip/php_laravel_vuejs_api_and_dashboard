import Vue from 'vue'
import Router from 'vue-router'
import UsersPage from "./components/admin/pages/UsersPage";
import UserCreatePage from "./components/admin/pages/UserCreatePage";
import UserEditPage from "./components/admin/pages/UserEditPage";
import UserDetailsPage from "./components/admin/pages/UserDetailsPage";
import AdCreatePage from "./components/admin/pages/AdCreatePage";
import AdEditPage from "./components/admin/pages/AdEditPage";
import PropertyCreatePage from "./components/admin/pages/PropertyCreatePage";
import PropertyEditPage from "./components/admin/pages/PropertyEditPage";
import PropertyDetailsPage from "./components/admin/pages/PropertyDetailsPage";
import StatsPage from './components/admin/pages/StatsPage.vue'
import RegisterPage from './components/admin/auth/RegisterPage.vue'
import LoginPage from './components/admin/auth/LoginPage.vue'
import NProgress from 'nprogress'

Vue.use(Router)

let adminRouterPrefix = document.head.querySelector('meta[name="admin-router-prefix"]')
adminRouterPrefix = adminRouterPrefix ? adminRouterPrefix.content : ''
window.adminRouterPrefix = adminRouterPrefix

const router = new Router({
    mode: 'history',
    routes: [
        /*
        {
            path: '/admin/register',
            component: RegisterPage,
            name: 'RegisterPage',
            meta: {
                title: 'page_register',
                requiresAuth: false
            },
        },
        */
        {
            path: `${adminRouterPrefix}/admin/login`,
            component: LoginPage,
            name: 'LoginPage',
            meta: {
                title: 'page_login',
                requiresAuth: false
            },
        },
        {
            path: `${adminRouterPrefix}/admin/developers`,
            component: UsersPage,
            name: 'UsersPage',
            meta: {
                title: 'page_users',
                requiresAuth: true
            },
            props: true,
        },
        {
            path: `${adminRouterPrefix}/admin/developers/create`,
            component: UserCreatePage,
            name: 'UserCreatePage',
            meta: {
                title: 'page_user_create',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:id/edit`,
            component: UserEditPage,
            name: 'UserEditPage',
            meta: {
                title: 'page_user_edit',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:id`,
            component: UserDetailsPage,
            name: 'UserDetailsPage',
            meta: {
                title: 'page_user_details',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:id/ads/create`,
            component: AdCreatePage,
            name: 'AdCreatePage',
            meta: {
                title: 'page_ad_create',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:userId/ads/:adId/edit`,
            component: AdEditPage,
            name: 'AdEditPage',
            meta: {
                title: 'page_ad_edit',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:userId/projects/create`,
            component: PropertyCreatePage,
            name: 'PropertyCreatePage',
            meta: {
                title: 'page_property_create',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:userId/projects/:propertyId/edit`,
            component: PropertyEditPage,
            name: 'PropertyEditPage',
            meta: {
                title: 'page_property_edit',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/developers/:userId/projects/:propertyId`,
            component: PropertyDetailsPage,
            name: 'PropertyDetailsPage',
            meta: {
                title: 'page_property_details',
                requiresAuth: true
            }
        },
        {
            path: `${adminRouterPrefix}/admin/stats`,
            component: StatsPage,
            name: 'StatsPage',
            meta: {
                title: 'page_stats',
                requiresAuth: true
            },
        },
        {
            path: '*',
            redirect: `${adminRouterPrefix}/admin/developers`,
        }
    ]
})

router.beforeEach((routeTo, routeFrom, next) => {
    NProgress.configure({
        showSpinner: false
    });
    NProgress.start()
    const loggedIn = localStorage.getItem('user')

    if (routeTo.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
        next(`${adminRouterPrefix}/admin/login`)
    }

    if (routeTo.matched.some(record => !record.meta.requiresAuth) && loggedIn) {
        next(`${adminRouterPrefix}/admin/developers`)
    }

    next()
})

router.afterEach((routeTo, routeFrom, next) => {
    NProgress.done()
})

export default router