<template>
    <div class="modal fade" id="adLeadsModal" tabindex="-1" role="dialog" aria-labelledby="adLeadsModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ trans.get('ad') }} #{{ ad.id }}
                        <i class="fas fa-long-arrow-alt-right"></i>{{ trans.get('leads') }}
                    </h5>
                    <button type="button" class="close" @click="getReportThree()" :disabled="downloadInProgress">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ad-leads-table :leads="leads" v-if="leads && Object.keys(leads).length"></ad-leads-table>
                    <pagination :meta="leadsMeta" componentName="AdLeadsTable" v-if="leadsMeta"></pagination>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">
                        {{ trans.get('close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AdLeadsTable from "../AdLeadsTable";
    import ReportService from "../../../services/ReportService";
    import store from "../../../store/store";
    import {mapState} from "vuex";
    import Pagination from "../Pagination";

    export default {
        name: "AdLeadsModal",
        components: {AdLeadsTable, Pagination},
        data() {
            return {
                ad: {},
                downloadInProgress: false
            }
        },
        methods: {
            closeModal() {
                this.ad = {}
                $('#adLeadsModal').modal('hide')
            },
            getReportThree() {
                if (!this.hasLeads) {
                    return false
                }

                let reportData = {}
                reportData.ad_id = this.ad.id

                this.downloadInProgress = true
                ReportService.getReportThree(reportData).then(() => {
                    this.downloadInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.downloadInProgress = false
                });
            }
        },
        created() {
            const currentPage = 1

            this.$root.$on('showAdLeadsModal', (ad) => {
                this.$store.commit('ad/SET_AD_DATA', ad)
                this.operationInProgress = true
                store.dispatch('ad/fetchAdLeads', {
                    adId: ad.id,
                    page: currentPage
                }).then(() => {
                    this.ad = ad
                    this.operationInProgress = false
                    $('#adLeadsModal').modal('show')
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage().then(() => {
                        this.operationInProgress = false
                        $('#adLeadsModal').modal('hide')
                    })
                })
            })
        },
        computed: {
            hasLeads() {
                return this.leads && Object.keys(this.leads).length > 0
            },
            ...mapState({
                leads: state => state.ad.adLeads,
                leadsMeta: state => state.ad.adLeadsMeta,
            }),
        }
    }
</script>

<style scoped>

</style>