<template>
    <div class="container">

        <div class="content-title py-4 d-flex justify-content-between align-items-center">
            <div class="btn-toolbar ml-auto">
                <router-link :to="{name: 'UserDetailsPage', params: {'id': user.id}}" tag="a"
                             class="btn btn-outline-dark ml-auto">
                    <i class="fas fa-long-arrow-alt-left"></i>{{ trans.get('back') }}
                </router-link>
            </div>
        </div>

        <div class="developers-list shadow p-3 mb-4 rounded">
            <user-item :user="user" :showPropertiesLabel="false" :showAdsLabel="false" :showDetailsButton="false" :showEditButton="false"
                       :showDestroyButton="false"></user-item>
        </div>

        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storeAd()" id="adForm">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input type="date" id="from_date" :label="trans.get('from_date')" :errorsData=errors
                                v-model="adData.from_date"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input type="date" id="to_date" :label="trans.get('to_date')" :errorsData=errors
                                v-model="adData.to_date"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="price" :label="trans.get('price')" :errorsData=errors
                                v-model="adData.price"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="price_lead" :label="trans.get('price_lead')" :errorsData=errors
                                v-model="adData.price_lead"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <base-input id="seconds" :label="trans.get('seconds')" :errorsData=errors
                                v-model="adData.seconds"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <base-select id="active" :label="trans.get('active')" :options="adForm.activeOptions"
                                 :errorsData=errors v-model="adData.active"></base-select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <base-input id="url" :label="trans.get('url')" :errorsData=errors v-model="adData.url"></base-input>
                </div>
            </div>

            <hr/>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input-file id="image" :label="trans.get('image')" :errorsData=errors
                                accept=".jpg, .jpeg, .png" v-model="adData.image"></base-input-file>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="external_image_url" :label="trans.get('external_image_url')" :errorsData=errors
                                v-model="adData.external_image_url"></base-input>
                </div>
            </div>

            <hr/>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <base-select id="areas" :label="trans.get('areas')" :options="adForm.areaOptions"
                                 :errorsData=errors v-model="adData.areas" :multiple="true"></base-select>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ trans.get('create') }}
                </button>
            </div>

        </form>

    </div>
</template>

<script>
    import store from "../../../store/store";
    import {mapState} from 'vuex'
    import UserItem from "../UserItem";
    import BaseInput from "../base/BaseInput";
    import BaseInputFile from "../base/BaseInputFile";
    import BaseSelect from "../base/BaseSelect";
    import {formMixin} from "../../../mixins/formMixin";
    import Swal from 'sweetalert2'
    import AdService from "../../../services/AdService";

    export default {
        name: "AdCreatePage",
        components: {UserItem, BaseInput, BaseInputFile, BaseSelect},
        mixins: [formMixin],
        beforeRouteEnter(routeTo, routeFrom, next) {
            store.dispatch('user/fetchUser', {
                id: routeTo.params.id
            }).then(() => {
                store.dispatch('ad/fetchAreas').then(() => {
                    next()
                }).catch(() => {
                    next()
                })
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
                adData: {},
                operationInProgress: false,
            }
        },
        methods: {
            storeAd() {
                this.adData.developer_id = this.user.id

                AdService.storeAd(this.adData).then(() => {
                    this.successMessage(this.trans.get('ad_has_been_created')).then(() => {
                        this.operationInProgress = false
                        this.$router.push({
                            name: 'UserDetailsPage',
                            params: {
                                'id': this.user.id
                            }
                        })
                    })
                }).catch(error => {
                    this.errors = error.response.data.error
                    console.log(this.errors)
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#adForm')
                })
            }
        },
        computed: {
            ...mapState({
                user: state => state.user.user,
                areas: state => state.ad.areas
            }),
        },
    }
</script>

<style scoped>

</style>