<template>
    <div id="reportOne">
        <div class="h2">{{ trans.get('report') }}</div>
        <p>{{ trans.get('report_one_description') }}</p>
        <form @submit.prevent="download()">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input type="date" id="from_date" :label="trans.get('from_date')" :errorsData=errors
                                v-model="reportData.from_date"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input type="date" id="to_date" :label="trans.get('to_date')" :errorsData=errors
                                v-model="reportData.to_date"></base-input>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" :disabled="!canDownload || operationInProgress">
                    {{ trans.get('download') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import BaseInput from "./base/BaseInput";
    import ReportService from "../../services/ReportService";

    export default {
        name: "ReportOne",
        components: {BaseInput},
        props: {
            userId: {
                required: true
            }
        },
        data() {
            return {
                reportData: {},
                operationInProgress: false
            }
        },
        methods: {
            download() {
                this.operationInProgress = true

                this.reportData.user_id = this.userId

                ReportService.getReportOne(this.reportData)
                    .then(() => {
                        this.operationInProgress = false
                    }).catch(error => {
                        this.errors = error.response.data.error
                        this.errorMessage()
                        this.validationAnimation('#reportOne')
                        this.operationInProgress = false
                });
            }
        },
        computed: {
            canDownload() {
                return this.reportData.from_date && this.reportData.to_date
            }
        }
    }
</script>

<style scoped>

</style>