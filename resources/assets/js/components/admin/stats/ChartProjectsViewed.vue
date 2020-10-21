<template>
    <div>
        <div class="card">
            <div class="card-header">
                {{ trans.get('number_of_projects_viewed') }}
            </div>
            <div class="card-body">
                <div class="text-center" v-if="operationInProgress">
                    <div class="spinner-grow" role="status">
                        <span class="sr-only">{{ trans.get('loading') }}</span>
                    </div>
                </div>
                <div v-show="!operationInProgress">
                    <canvas id="chart-projects-viewed" v-if="!error"></canvas>
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
        name: "chartProjectsViewed",
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
                            }]
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
                        }
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

                StatsService.chartProjectsViewed(this.fromDate, this.toDate).then(response => {
                    this.operationInProgress = false;
                    let data = response.data;
                   
                    if (data && !Object.keys(data).length) {
                        return;
                    }

                    let labels = [];
                    let datasets = [];
                    let dataset = {
                        label: this.trans.get('projects_viewed'),
                        backgroundColor: '#00E676',
                        borderColor: '#00C853',
                        borderWidth: 1,
                        data: [],
                    };

                    (data.data.viewed).forEach(item => {
                        labels.push(item.x);
                        (dataset.data).push(item.y ? item.y : 0.1);
                    });

                    datasets.push(dataset);

                    this.chartData.data.labels = labels;
                    this.chartData.data.datasets = datasets;

                    this.createChart('chart-projects-viewed', this.chartData);
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false;
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