import axios from 'axios'
import AxiosService from "./AxiosService";

export default {
    getPlans() {
        AxiosService.setHeaders(axios)
        return axios.get(`/api/stripe/plans`)
    },
}