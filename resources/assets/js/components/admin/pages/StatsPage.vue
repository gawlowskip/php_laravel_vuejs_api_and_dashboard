<template>
    <div class="container">

        <div class="content-title py-4 d-flex justify-content-between align-items-center">
            <div class="btn-toolbar ml-auto">
                <div class="input-group mr-2">
                    <base-input id="fromDate" type="date" :placeholder="trans.get('from_date')" :errorsData=errors
                                v-model="dates.fromDate"></base-input>
                </div>
                <div class="input-group mr-2">
                    <base-input id="toDate" type="date" :placeholder="trans.get('to_date')" :errorsData=errors
                                v-model="dates.toDate"></base-input>
                </div>
                <button class="btn btn-outline-dark ml-auto" @click="changeDate">{{ trans.get('change_date') }}</button>
            </div>
        </div>

        <div class="shadow p-3 mb-4 rounded">
            <div class="row rounded">
                <div class="col-12">
                    <chart-projects-created :fromDate=dates.fromDate :toDate=dates.toDate></chart-projects-created>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 mb-lg-3 mb-md-3 mb-sm-3">
                <chart-projects-viewed :fromDate=dates.fromDate :toDate=dates.toDate></chart-projects-viewed>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-12">
                <list-projects-who-viewed :fromDate=dates.fromDate :toDate=dates.toDate></list-projects-who-viewed>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 mb-lg-3 mb-md-3 mb-sm-3">
                <chart-users-logins-and-signups :fromDate=dates.fromDate :toDate=dates.toDate></chart-users-logins-and-signups>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-12">
                <chart-payments-verified-unverified :fromDate=dates.fromDate :toDate=dates.toDate></chart-payments-verified-unverified>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 mb-lg-3 mb-md-3 mb-sm-3">
                <chart-developers-without-agreement :fromDate=dates.fromDate :toDate=dates.toDate></chart-developers-without-agreement>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-12">

            </div>
        </div>
    </div>
</template>

<script>
    import BaseInput from "../base/BaseInput"
    import ChartProjectsCreated from "../stats/ChartProjectsCreated"
    import ChartProjectsViewed from "../stats/ChartProjectsViewed"
    import ListProjectsWhoViewed from "../stats/ListProjectsWhoViewed"
    import ChartUsersLoginsAndSignups from "../stats/ChartUsersLoginsAndSignups"
    import ChartPaymentsVerifiedUnverified from "../stats/ChartPaymentsVerifiedUnverified"
    import ChartDevelopersWithoutAgreement from "../stats/ChartDevelopersWithoutAgreement";

    export default {
        name: "StatsPage",
        components: {
            BaseInput, ChartProjectsCreated, ChartProjectsViewed, ListProjectsWhoViewed,
            ChartUsersLoginsAndSignups, ChartPaymentsVerifiedUnverified, ChartDevelopersWithoutAgreement},
        data() {
            return {
                dates: {
                    fromDate: moment().subtract(14, 'days').format('YYYY-MM-DD'),
                    toDate: moment().format('YYYY-MM-DD')
                }
            }
        },
        watch: {
            'dates.fromDate': function(fromDate) {
                fromDate = moment(fromDate, 'YYYY-MM-DD');
                let toDate = moment(this.dates.toDate, 'YYYY-MM-DD');

                if (toDate.diff(fromDate, 'days') > 14) {
                    toDate = fromDate.add(14, 'days');
                    this.dates.toDate = toDate.format('YYYY-MM-DD');
                }

                if (fromDate.isAfter(toDate)) {
                    this.dates.toDate = fromDate.format('YYYY-MM-DD');
                }
            },
            'dates.toDate': function(toDate) {
                let fromDate = moment(this.dates.fromDate, 'YYYY-MM-DD');
                toDate = moment(this.dates.toDate, 'YYYY-MM-DD');

                if (toDate.diff(fromDate, 'days') > 14) {
                    toDate = fromDate.add(14, 'days');
                    this.dates.toDate = toDate.format('YYYY-MM-DD');
                }

                if (fromDate.isAfter(toDate)) {
                    this.dates.toDate = fromDate.format('YYYY-MM-DD');
                }
            },
        },
        methods: {
            changeDate() {
                this.$root.$emit('changeDate');
            }
        }
    }
</script>

<style scoped>

</style>