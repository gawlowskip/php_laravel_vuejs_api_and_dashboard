import axios from 'axios'
import AxiosService from "./AxiosService";

export default {
    getAreas() {
        AxiosService.setHeaders(axios)
        return axios.get(`/api/areas`)
    },
}