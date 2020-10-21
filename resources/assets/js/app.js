/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import router from "./router"
import store from './store/store'
import axios from 'axios'
import 'nprogress/nprogress.css'
import 'sweetalert2/dist/sweetalert2.min.css'
import Swal from 'sweetalert2'
import Trans from "./lang/Trans";
import 'animate.css/animate.min.css'
import AxiosService from "./services/AxiosService";

window.Vue = require('vue');
window.Chart = require('chart.js');
window.moment = require('moment');

const locale = 'en'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* Admin Layout */
Vue.component('AdminMainPage', require('./components/admin/pages/AdminMainPage'));

Vue.mixin({
    data() {
        return {
            errors: null,
            trans: {}
        }
    },
    methods: {
        validationError(inputName) {
            if (!this.errors || !inputName) {
                return null
            }

            let type = typeof this.errors

            if (type !== 'object') {
                return null
            }

            let error = this.errors[inputName]
            return error !== undefined ? error[0] : null
        },
        errorMessage(message) {
            if (message) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
            }

            let type = typeof this.errors

            if (type !== 'string') {
                return null
            }

            return Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: this.errors,
            })
        },
        successMessage(message) {
            let timerInterval

            return Swal.fire({
                title: this.trans.get('success'),
                timer: 5000,
                text: message,
                icon: 'success',
                showCloseButton: true,
                onClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    /* Closed by timer */
                }
            })
        },
        confirmMessage(title) {
            return Swal.fire({
                title: title,
                text: this.trans.get('you_will_not_be_able_to_revert_this'),
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: this.trans.get('cancel'),
                confirmButtonText: this.trans.get('delete')
            })
        },
        animateElement(elementId, className, customOptions) {
            if (typeof this.errors === 'string') {
                return false
            }

            let options = {
                startDelay: 0,
                removeAnimation: true,
                removeAnimationDelay: 1000
            }

            if (customOptions !== undefined && typeof customOptions === 'object') {
                if (customOptions.startDelay !== undefined) {
                    options.startDelay = customOptions.startDelay
                }

                if (customOptions.removeAnimation !== undefined) {
                    options.removeAnimation = customOptions.removeAnimation
                }

                if (customOptions.removeAnimationDelay !== undefined) {
                    options.removeAnimationDelay = customOptions.removeAnimationDelay
                }

                if (customOptions.speed) {
                    options.speed = customOptions.speed
                }
            }

            const element = document.querySelector(elementId)

            setTimeout(() => {
                element.classList.add('animated', className)
                if (options.speed) {
                    element.classList.add(options.speed)
                }
            }, options.startDelay)

            if (options.removeAnimation) {
                setTimeout(() => {
                    element.classList.remove('animated', className)
                    if (options.speed) {
                        element.classList.remove(options.speed)
                    }
                }, options.startDelay + options.removeAnimationDelay)
            }
        },
        cancelAnimation(elementId, customOptions) {
            let options = {
                startDelay: 100
            }

            if (customOptions) {
                options = customOptions
            }

            this.animateElement(elementId, 'bounce', options)
        },
        validationAnimation(elementId, customOptions) {
            let options = {}

            if (customOptions) {
                options = customOptions
            }

            this.animateElement(elementId, 'shake', options)
        }
    },
    created() {
        this.trans = new Trans(locale)
    }
})

const app = new Vue({
    router,
    store,
    created() {
        const userString = localStorage.getItem('user')

        if (userString) {
            const userData = JSON.parse(userString)
            this.$store.commit('auth/SET_AUTH_USER_DATA', userData)
        }

        let mainUrl = document.head.querySelector('meta[name="main-url"]')
        if (mainUrl) {
            window.mainUrl = mainUrl.content
        }

        AxiosService.setHeaders(axios)

        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 401) {
                    let data = {
                        reload: false
                    }
                    this.$store.dispatch('auth/logout', data)
                }
                return Promise.reject(error)
            })

        axios.interceptors.response.use(
            response => {
                return response;
            },
            error => {
                if (error.request.responseType === 'blob' &&
                    error.response.data instanceof Blob &&
                    error.response.data.type &&
                    error.response.data.type.toLowerCase().indexOf('json') !== -1
                ) {
                    return new Promise((resolve, reject) => {
                        let reader = new FileReader()
                        reader.onload = () => {
                            error.response.data = JSON.parse(reader.result)
                            resolve(Promise.reject(error))
                        }

                        reader.onerror = () => {
                            reject(error)
                        }

                        reader.readAsText(error.response.data)
                    })
                }

                return Promise.reject(error)
            }
        )
    },
    el: '#app'
});