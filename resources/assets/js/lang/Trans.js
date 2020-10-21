import axios from 'axios'
import en from "./en";
import pl from "./pl";
import AxiosService from "../services/AxiosService";

class Trans {
    constructor(locale) {
        this._fallback_locale = en
        this._locale = locale
        this.setLocale(locale)
    }

    set locale(locale) {
        this._locale = locale
    }

    set trans(trans) {
        this._trans = trans
    }

    get(name, options) {
        if (!name || !this._trans[name]) {
            return `#${this._locale}.${name}`
        }

        if (options && typeof options === 'object') {
            let string = this._trans[name]

            Object.keys(options).forEach((key) => {
                string = string.replace(`:${key}`, options[key])
            })

            return string
        }

        return this._trans[name]
    }

    setLocale(locale) {
        let data = {
            locale: locale
        }

        if (!this.trans) {
            this.trans = this._fallback_locale
        }

        let currentLocale = localStorage.getItem('locale')

        if (currentLocale && currentLocale === locale) {
            return true
        }

        AxiosService.setHeaders(axios)
        axios.post('/api/locale', data).then(() => {
            this.locale = locale

            switch (locale) {
                case 'en':
                    this.trans = en
                    break
                case 'pl':
                    this.trans = pl
                    break
                default:
                    this.trans = this._fallback_locale
                    break
            }

            localStorage.setItem('locale', locale)
        }).catch(error => {

        })
    }
}

export default Trans