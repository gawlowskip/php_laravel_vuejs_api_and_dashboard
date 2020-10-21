export default {
    /* TODO: Set headers to fix problem with reload page on server */
    setHeaders(axios) {
        const userString = localStorage.getItem('user')

        if (userString) {
            const userData = JSON.parse(userString)
            axios.defaults.headers.common['Authorization'] = `Bearer ${userData.meta.token}`
        }

        let baseUrl = document.head.querySelector('meta[name="base-url"]')
        if (baseUrl) {
            axios.defaults.baseURL = baseUrl.content
        }

        axios.defaults.headers.common['Accept'] = 'application/json'
        axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
        axios.defaults.headers.common['Content-Type'] = 'application/json'
    },
    buildFormData(formData, data, parentKey) {
        if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
            Object.keys(data).forEach(key => {
                this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key)
            })
        } else {
            const value = data == null ? '' : data

            formData.append(parentKey, value)
        }
    },
    jsonToFormData(data) {
        const formData = new FormData()

        this.buildFormData(formData, data)

        return formData;
    },
    rebuildResponse(data, meta) {
        let response = {
            data: [],
            meta: meta
        }

        data.forEach((item) => {
            (response.data).push(item)
        })

        return response
    },
}