<template>
    <div class="container">

        <div class="content-title py-4 d-flex justify-content-between align-items-center">
            <div class="btn-toolbar ml-auto">
                <router-link :to="{name: 'UserDetailsPage', params: {'id': user.id}}" tag="a" class="btn btn-outline-dark ml-auto" v-if="step === 1">
                    <i class="fas fa-long-arrow-alt-left"></i>{{ trans.get('back') }}
                </router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card" :class="{'border-dark': step === 1}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step === 1"></i>
                            <i class="fas fa-check-square" v-else-if="step > 1"></i>
                            {{ trans.get('step', {'step_no': 1}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('create_project') }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card" :class="{'border-dark': step === 2}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step <= 2"></i>
                            <i class="fas fa-check-square" v-else-if="step > 2"></i>
                            {{ trans.get('step', {'step_no': 2}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('add_features') }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card" :class="{'border-dark': step === 3}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step <= 3"></i>
                            <i class="fas fa-check-square" v-else-if="step > 3"></i>
                            {{ trans.get('step', {'step_no': 3}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('add_location') }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card" :class="{'border-dark': step === 4}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step <= 4"></i>
                            <i class="fas fa-check-square" v-else-if="step > 4"></i>
                            {{ trans.get('step', {'step_no': 4}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('add_images') }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card" :class="{'border-dark': step === 5}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step <= 5"></i>
                            <i class="fas fa-check-square" v-else-if="step > 5"></i>
                            {{ trans.get('step', {'step_no': 5}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('add_videos') }}</h6>
                    </div>
                </div>
            </div>

        </div>

        <!-- Step 1 - General -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storeProperty()" v-if="step === 1" id="propertyForm">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="description" :label="trans.get('description')" :errorsData=errors
                                v-model="propertyData.description"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="editing_hash" :label="trans.get('editing_hash')" :errorsData=errors
                                v-model="propertyData.editing_hash"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="bricklayer" :label="trans.get('bricklayer')" :errorsData=errors
                                v-model="propertyData.bricklayer"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="carpenter" :label="trans.get('carpenter')" :errorsData=errors
                                v-model="propertyData.carpenter"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="electrician" :label="trans.get('electrician')" :errorsData=errors
                                v-model="propertyData.electrician"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="vvs" :label="trans.get('vvs')" :errorsData=errors
                                v-model="propertyData.vvs"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="entrepreneur" :label="trans.get('entrepreneur')" :errorsData=errors
                                v-model="propertyData.entrepreneur"></base-input>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">{{
                    trans.get('create') }}
                </button>
            </div>

        </form>
        <!-- / Step 1 - General -->

        <!-- Step 2 - Features -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storePropertyFeatures()" v-if="step === 2" id="propertyFeaturesForm">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="property_type" :label="trans.get('property_type')" :errorsData=errors
                                v-model="propertyFeaturesData.property_type"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="material" :label="trans.get('material')" :errorsData=errors
                                v-model="propertyFeaturesData.material"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="completion_date" type="date" :label="trans.get('completion_date')"
                                :errorsData=errors v-model="propertyFeaturesData.completion_date"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="size" :label="trans.get('size')" :errorsData=errors
                                v-model="propertyFeaturesData.size"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="rooms_amount" :label="trans.get('rooms')" :errorsData=errors
                                v-model="propertyFeaturesData.rooms_amount"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="baths_amount" :label="trans.get('baths')" :errorsData=errors
                                v-model="propertyFeaturesData.baths_amount"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="bedrooms_amount" :label="trans.get('bedrooms')" :errorsData=errors
                                v-model="propertyFeaturesData.bedrooms_amount"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="floors" :label="trans.get('floors')" :errorsData=errors
                                v-model="propertyFeaturesData.floors"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="price" :label="trans.get('price')" :errorsData=errors
                                v-model="propertyFeaturesData.price"></base-input>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">{{
                    trans.get('add_features') }}
                </button>
                <button type="button" class="btn btn-outline-dark ml-2" name="button" @click="skipStep()"
                        :disabled="operationInProgress">{{ trans.get('skip') }}
                </button>
            </div>

        </form>
        <!-- / Step 2 - Features -->

        <!-- Step 3 - Location -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storePropertyLocation()" v-if="step === 3" id="propertyLocationForm">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="district" :label="trans.get('district')" :errorsData=errors
                                v-model="propertyLocationData.district"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="city" :label="trans.get('city')" :errorsData=errors
                                v-model="propertyLocationData.city"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="street" :label="trans.get('street')" :errorsData=errors
                                v-model="propertyLocationData.street"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="latitude" :label="trans.get('latitude')" :errorsData=errors
                                v-model="propertyLocationData.latitude"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="longitude" :label="trans.get('longitude')" :errorsData=errors
                                v-model="propertyLocationData.longitude"></base-input>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">{{
                    trans.get('add_location') }}
                </button>
                <button type="button" class="btn btn-outline-dark ml-2" name="button" @click="skipStep()"
                        :disabled="operationInProgress">{{ trans.get('skip') }}
                </button>
            </div>

        </form>
        <!-- / Step 3 - Location -->

        <!-- Step 4 - Images -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storePropertyImages()" v-if="step === 4" id="propertyImagesForm">

            <div class="form-row">
                <div class="form-group col-md-12">
                    <base-input-file id="images" :label="trans.get('images')" :errorsData=errors :multiple="true"
                                     accept=".jpg, .jpeg, .png" v-model="propertyImagesData.images"></base-input-file>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ trans.get('add_images') }}
                </button>
                <button type="button" class="btn btn-outline-dark ml-2" name="button" @click="skipStep()"
                        :disabled="operationInProgress">{{ trans.get('skip') }}
                </button>
            </div>

        </form>
        <!-- / Step 4 - Images -->

        <!-- Step 5 - Videos -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storePropertyVideos()" v-if="step === 5" id="propertyVideosForm">

            <div class="form-row">
                <div class="form-group col-md-12">
                    <base-input-file id="videos" :label="trans.get('videos')" :errorsData=errors :multiple="true"
                                     accept=".mov, .mp4, .avi" v-model="propertyVideosData.videos"></base-input-file>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ trans.get('add_videos') }}
                </button>
                <button type="button" class="btn btn-outline-dark ml-2" name="button" @click="skipStep()"
                        :disabled="operationInProgress">{{ trans.get('skip') }}
                </button>
            </div>

        </form>
        <!-- / Step 5 - Videos -->

    </div>
</template>

<script>
    import store from "../../../store/store";
    import BaseInput from "../base/BaseInput";
    import BaseInputFile from "../base/BaseInputFile";
    import {mapState} from 'vuex'
    import Swal from 'sweetalert2'
    import PropertyService from "../../../services/PropertyService";

    export default {
        name: "PropertyCreatePage",
        components: {BaseInput, BaseInputFile},
        beforeRouteEnter(routeTo, routeFrom, next) {
            store.dispatch('user/fetchUser', {
                id: routeTo.params.userId
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
                propertyData: {},
                propertyImagesData: {},
                propertyVideosData: {},
                propertyFeaturesData: {},
                propertyLocationData: {},
                step: 1,
                operationInProgress: false
            }
        },
        methods: {
            storeProperty() {
                let data = {...this.propertyData}

                data.developer = this.user

                PropertyService.storeProperty(data).then(response => {
                    this.propertyData.id = response.data.data.id
                    this.step = 2
                    this.operationInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyForm')
                })
            },
            storePropertyFeatures() {
                PropertyService.storePropertyFeatures(this.propertyData.id, this.propertyFeaturesData).then(() => {
                    this.step = 3
                    this.operationInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyFeaturesForm')
                })
            },
            storePropertyLocation() {
                PropertyService.storePropertyLocation(this.propertyData.id, this.propertyLocationData).then(() => {
                    this.step = 4
                    this.operationInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyLocationForm')
                })
            },
            storePropertyImages() {
                PropertyService.storePropertyImages(this.propertyData.id, this.propertyImagesData).then(() => {
                    this.step = 5
                    this.operationInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyImagesForm')
                })
            },
            storePropertyVideos() {
                PropertyService.storePropertyVideos(this.propertyData.id, this.propertyVideosData).then(() => {
                    this.step = 6
                    this.successMessage(this.trans.get('project_has_been_created')).then(() => {
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
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyVideosForm')
                })
            },
            skipStep() {
                if (this.step === 5) {
                    this.successMessage(this.trans.get('project_has_been_created')).then(() => {
                        this.operationInProgress = false
                        this.$router.push({
                            name: 'UserDetailsPage',
                            params: {
                                'id': this.user.id
                            }
                        })
                    })
                }

                this.step++
            }
        },
        computed: {
            ...mapState({
                user: state => state.user.user,
            }),
        },
    }
</script>

<style scoped>

</style>