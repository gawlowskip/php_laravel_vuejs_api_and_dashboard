<template>
    <div class="container">
        <div class="btn-toolbar py-4">
            <div class="btn-toolbar ml-auto">
                <router-link :to="{name: 'UsersPage'}" tag="a" class="btn btn-outline-dark ml-auto">
                    <i class="fas fa-long-arrow-alt-left"></i>{{ trans.get('back') }}
                </router-link>
            </div>
        </div>

        <div class="developers-list shadow p-3 mb-4 rounded">
            <user-item :user="user" :showPropertiesLabel="false" :showAdsLabel="false" :showDetailsButton="false" :showDestroyButton="false">
            </user-item>
        </div>

        <!-- TODO: Upcoming feature -->
        <div class="project-list shadow p-3 mb-4 rounded" v-if="false">

            <div class="h2 py-3">
                {{ trans.get('agreement') }} <span class="badge badge-light">{{ user.is_active_developer ? '1' : '0' }}</span>
            </div>

            <agreements :developerId="user.id" v-if="user.is_active_developer"></agreements>
        </div>

        <div class="reports shadow p-3 mb-4 rounded">
            <report-one :userId="user.id"></report-one>
        </div>

        <div class="advert-list shadow p-3 mb-4 rounded">
            <div class="h2 py-3">
                {{ trans.get('ads') }}
                <span class="badge badge-light">{{ countAds }}</span>
                <div class="btn-toolbar float-right">
                    <button class="btn btn-outline-dark" @click="getReportTwo()"
                            :disabled="!hasAds || downloadInProgress">{{ trans.get('download_pdf') }}
                    </button>
                    <router-link :to="{name: 'AdCreatePage'}" tag="a" class="btn btn-primary ml-2">
                        <i class="fas fa-plus"></i>{{ trans.get('create') }}
                    </router-link>
                </div>
            </div>

            <ads-table :userId="user.id" v-if="hasAds"></ads-table>
            <p v-if="!user.active">{{ trans.get('note_user_is_inactive') }}</p>
            <!-- TODO: When pagination, problem with deleting elements -->
            <pagination :meta="adsMeta" componentName="AdsTable"></pagination>
        </div>

        <div class="project-list shadow p-3 mb-4 rounded">

            <div class="h2 py-3">
                {{ trans.get('projects') }} <span class="badge badge-light">{{ countProjects }}</span>
                <div class="btn-toolbar float-right">
                    <router-link :to="{name: 'PropertyCreatePage', params: {'userId': user.id}}" tag="a" class="btn btn-primary ml-2">
                        <i class="fas fa-plus"></i>{{ trans.get('create') }}
                    </router-link>
                </div>
            </div>

            <properties :userId="user.id" v-if="hasProjects"></properties>
            <p v-if="!user.active">{{ trans.get('note_user_is_inactive') }}</p>
            <!-- TODO: When pagination, problem with deleting elements -->
            <pagination :meta="propertiesMeta" componentName="Properties"></pagination>
        </div>
    </div>
</template>

<script>
    import BaseInput from "../base/BaseInput";
    import BaseSelect from "../base/BaseSelect";
    import store from "../../../store/store";
    import {mapState} from 'vuex'
    import Swal from 'sweetalert2'
    import UserItem from "../UserItem";
    import ReportOne from "../ReportOne"
    import AdsTable from "../AdsTable";
    import ReportService from "../../../services/ReportService";
    import Properties from "../Properties";
    import Pagination from "../Pagination";
    import Agreements from "../Agreements";

    export default {
        name: "UserDetailsPage",
        components: {Agreements, BaseSelect, BaseInput, UserItem, ReportOne, AdsTable, Properties, Pagination},
        beforeRouteEnter(routeTo, routeFrom, next) {
            store.dispatch('user/fetchUser', {
                id: routeTo.params.id
            }).then(() => {
                next()
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.response.data.error,
                }).then(() => {
                    next(routeFrom)
                })
            })
        },
        data() {
            return {
                downloadInProgress: false,
            }
        },
        methods: {
            getReportTwo() {
                if (!this.hasAds) {
                    return false
                }

                let reportData = {}
                reportData.developer_id = this.user.id

                this.downloadInProgress = true
                ReportService.getReportTwo(reportData)
                    .then(() => {
                        this.downloadInProgress = false
                    }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.downloadInProgress = false
                });
            }
        },
        computed: {
            ...mapState({
                user: state => state.user.user,
                ads: state => state.developer.developerAds,
                adsMeta: state => state.developer.developerAdsMeta,
                properties: state => state.developer.developerProperties,
                propertiesMeta: state => state.developer.developerPropertiesMeta,
            }),
            hasAds() {
                return this.countAds > 0
            },
            countAds() {
                if (this.ads) {
                    return this.ads.length
                }

                return this.user.count_ads
            },
            hasProjects() {
                return this.countProjects > 0
            },
            countProjects() {
                if (this.properties) {
                    return this.properties.length
                }

                return this.user.count_properties
            }
        },
    }
</script>

<style scoped>

</style>