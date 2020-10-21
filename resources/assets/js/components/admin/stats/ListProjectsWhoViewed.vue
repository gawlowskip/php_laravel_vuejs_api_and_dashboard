<template>
    <div>
        <div class="card">
            <div class="card-header">
                {{ trans.get('who_viewed_a_projects') }}
            </div>
            <div class="card-body" :class="{'fit-to-chart': !operationInProgress && !error}">
                <div class="text-center" v-if="operationInProgress">
                    <div class="spinner-grow" role="status">
                        <span class="sr-only">{{ trans.get('loading') }}</span>
                    </div>
                </div>
                <div v-show="!operationInProgress">
                    <ul class="list-group" v-if="!error">
                        <li class="list-group-item d-flex justify-content-between align-items-center" v-for="user in whoViewed" v-if="whoViewed && Object.keys(whoViewed).length">
                            {{ user.name }} {{ user.last_name }}
                            <span class="badge badge-primary badge-pill">{{ user.visits }} {{ trans.get('views') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center" v-if="whoViewed && !Object.keys(whoViewed).length">
                            {{ trans.get('during_this_period_no_one_viewed_projects') }}
                        </li>
                    </ul>
                    <div class="alert alert-danger" role="alert" v-if="error">
                        {{ error }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import StatsService from "../../../services/StatsService";

    export default {
        name: "listProjectsWhoViewed",
        props: {
            fromDate: {
                required: true
            },
            toDate: {
                required: true
            },
        },
        data() {
            return {
                whoViewed: {},
                error: '',
                operationInProgress: false,
            }
        },
        methods: {
            getListData() {
                this.error = '';
                this.operationInProgress = true;

                StatsService.listProjectsWhoViewed(this.fromDate, this.toDate).then(response => {
                    this.operationInProgress = false;
                    let data = response.data;

                    if (data && !Object.keys(data).length) {
                        return;
                    }

                    this.whoViewed = data.data.who_viewed;
                }).catch(error => {
                    this.operationInProgress = false;
                    this.errors = error.response.data.error
                    this.errorMessage()
                });
            },
        },
        mounted() {
            this.getListData();

            this.$root.$on('changeDate', () => {
                this.getListData();
            });
        }
    }
</script>

<style scoped>
    .fit-to-chart {
        height: 380px;
        overflow-y: auto;
    }
</style>