import axios from 'axios'
import AxiosService from "./AxiosService";

export default {
    chartProjectsCreated(fromDate, toDate) {
        let params = {
            params: {
                fromDate: fromDate,
                toDate: toDate
            }
        }
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stats/projects-created`, params)
    },
    chartProjectsViewed(fromDate, toDate) {
        let params = {
            params: {
                fromDate: fromDate,
                toDate: toDate
            }
        }
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stats/projects-viewed`, params)
    },
    listProjectsWhoViewed(fromDate, toDate) {
        let params = {
            params: {
                fromDate: fromDate,
                toDate: toDate
            }
        }
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stats/projects-who-viewed`, params)
    },
    chartUsersLoginsAndSignups(fromDate, toDate) {
        let params = {
            params: {
                fromDate: fromDate,
                toDate: toDate
            }
        }
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stats/users-logins-and-signups`, params)
    },
    chartPaymentsVerifiedUnverified(fromDate, toDate) {
        let params = {
            params: {
                fromDate: fromDate,
                toDate: toDate
            }
        }
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stats/payments-verified-and-unverified`, params)
    },
    chartDevelopersWithoutAgreements(fromDate, toDate) {
        let params = {
            params: {
                fromDate: fromDate,
                toDate: toDate
            }
        }
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stats/developers-without-agreement`, params)
    }
}