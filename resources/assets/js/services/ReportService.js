import axios from 'axios'
import AxiosService from "./AxiosService";

function download(response, fileName) {
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', fileName)
    document.body.appendChild(link)
    link.click()
}

export default {
    getReportOne(reportData) {
        AxiosService.setHeaders(axios)
        return axios({
            url: `/api/pdf/reportOne`,
            method: 'GET',
            params: reportData,
            responseType: 'blob',
        }).then((response) => {
            download(response, `Report - User ${reportData.user_id} - ${reportData.from_date} - ${reportData.to_date}.pdf`)
        })
    },
    getReportTwo(reportData) {
        AxiosService.setHeaders(axios)
        return axios({
            url: `/api/pdf/reportTwo`,
            method: 'GET',
            params: reportData,
            responseType: 'blob',
        }).then((response) => {
            download(response, `Report - Developer ${reportData.developer_id} - Ads.pdf`)
        })
    },
    getReportThree(reportData) {
        AxiosService.setHeaders(axios)
        return axios({
            url: `/api/pdf/reportThree`,
            method: 'GET',
            params: reportData,
            responseType: 'blob',
        }).then((response) => {
            download(response, `Report - Ad ${reportData.ad_id} - Leads.pdf`)
        })
    }
}