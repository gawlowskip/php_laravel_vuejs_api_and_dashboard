<template>
    <div>
        <div class="card">
            <div class="card-header">
                {{ trans.get('number_of_logins_and_signups') }}
            </div>
            <div class="card-body">
                <div class="text-center" v-if="operationInProgress">
                    <div class="spinner-grow" role="status">
                        <span class="sr-only">{{ trans.get('loading') }}</span>
                    </div>
                </div>
                <div v-show="!operationInProgress">
                    <canvas id="chart-users-logins-and-signups" v-if="!error"></canvas>
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
        name: "chartUsersLoginsAndSignups",
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
                chartData: {
                    type: 'bar',
                    data: {
                        datasets: [],
                        labels: [],
                    },
                    options: {
                        lineTension: 1,
                        scales: {
                            xAxes: [{
                                ticks: {
                                    autoSkip: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    suggestedMin: 0,
                                    suggestedMax: 5,
                                    beginAtZero: true,
                                }
                            }],
                        },
                        responsive: true,
                        tooltips: {
                            enabled: true,
                            callbacks: {
                                title: function (tooltipItems, data) {
                                    var tooltipItem = tooltipItems[0];
                                    return data.labels[tooltipItem.index];
                                },
                                label: function(tooltipItems, data) {
                                    var label = tooltipItems.yLabel;
                                    if (label === 0.1) {
                                        label = 0;
                                    }
                                    return data.datasets[tooltipItems.datasetIndex].label +': ' + label;
                                }
                            }
                        },
                    }
                },
                error: '',
                operationInProgress: false,
            }
        },
        methods: {
            createChart(chartId, chartData) {
                const ctx = document.getElementById(chartId);
                const myChart = new Chart(ctx, {
                    type: chartData.type,
                    data: chartData.data,
                    options: chartData.options,
                });
            },
            getChartData() {
                this.error = '';
                this.operationInProgress = true;

                StatsService.chartUsersLoginsAndSignups(this.fromDate, this.toDate).then(response => {
                    this.operationInProgress = false;
                    let data = response.data;

                    if (data && !Object.keys(data).length) {
                        return;
                    }

                    let labels = [];
                    let datasets = [];

                    /* Logins */
                    let dataset = {
                        label: this.trans.get('logins'),
                        backgroundColor: '#FF1744',
                        borderColor: '#D50000',
                        borderWidth: 1,
                        data: [],
                    };
                    (data.data.logins).forEach(item => {
                        labels.push(item.x);
                        (dataset.data).push(item.y ? item.y : 0.1);
                    });
                    datasets.push(dataset);

                    /* Signups */
                    dataset = {
                        label: this.trans.get('signups'),
                        backgroundColor: '#3D5AFE',
                        borderColor: '#304FFE',
                        borderWidth: 1,
                        data: [],
                    };
                    (data.data.signups).forEach(item => {
                        (dataset.data).push(item.y ? item.y : 0.1);
                    });
                    datasets.push(dataset);

                    this.chartData.data.labels = labels;
                    this.chartData.data.datasets = datasets;

                    this.createChart('chart-users-logins-and-signups', this.chartData);
                }).catch(error => {
                    this.operationInProgress = false;
                    this.errors = error.response.data.error
                    this.errorMessage()
                });
            },
        },
        mounted() {
            this.getChartData();

            this.$root.$on('changeDate', () => {
                this.getChartData();
            });
        }
    }
</script>

<style scoped>

</style>