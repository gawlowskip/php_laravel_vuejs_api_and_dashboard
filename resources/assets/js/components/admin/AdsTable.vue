<template>
    <div>
        <ad-leads-modal></ad-leads-modal>
        <table class="table table-hover rwd-table">
            <thead class="thead-light">
            <tr>
                <th></th>
                <th>{{ trans.get('price') }}</th>
                <th>{{ trans.get('price_lead') }}</th>
                <th>{{ trans.get('seconds') }}</th>
                <th>{{ trans.get('image') }}</th>
                <th>{{ trans.get('leads') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <ads-table-row :ad="ad" v-for="ad in ads" :key="ad.id" @deleteAdFromList="deleteAdFromList"></ads-table-row>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import store from "../../store/store";
    import AdsTableRow from "./AdsTableRow";
    import AdLeadsModal from "./modals/AdLeadsModal";
    import AxiosService from "../../services/AxiosService";

    export default {
        name: "UserAdsTable",
        components: {AdLeadsModal, AdsTableRow},
        props: {
            userId: {
                required: true
            }
        },
        methods: {
            deleteAdFromList(id) {
                let data = this.ads.filter(ad => ad.id !== id)
                let meta = null

                this.$store.commit('developer/SET_DEVELOPER_ADS_DATA', AxiosService.rebuildResponse(data, meta))
            },
        },
        created() {
            const currentPage = 1
            store.dispatch('developer/fetchDeveloperAds', {
                developerId: this.userId,
                page: currentPage
            })
        },
        computed: {
            ...mapState({
                ads: state => state.developer.developerAds
            })
        },
    }
</script>

<style scoped>

</style>