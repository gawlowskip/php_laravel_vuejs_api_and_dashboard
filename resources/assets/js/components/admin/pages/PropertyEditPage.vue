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

        <!-- Step 1 - General -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="updateProperty()" id="propertyForm">

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
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ trans.get('update_project_data') }}
                </button>
            </div>

        </form>
        <!-- / Step 1 - General -->

        <!-- Step 2 - Features -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="!hasFeatures ? storePropertyFeatures() : updatePropertyFeatures()" id="propertyFeaturesForm">

            <div class="h2 py-3">
                {{ trans.get('features') }}
            </div>

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
                    trans.get('update_project_features') }}
                </button>
            </div>

        </form>
        <!-- / Step 2 - Features -->

        <!-- Step 3 - Location -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="!hasLocation ? storePropertyLocation() : updatePropertyLocation()" id="propertyLocationForm">

            <div class="h2 py-3">
                {{ trans.get('location') }}
            </div>

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
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ trans.get('update_project_location') }}
                </button>
            </div>

        </form>
        <!-- / Step 3 - Location -->

        <!-- Step 4 - Images -->
        <div class="shadow p-3 mb-4 rounded">

            <div class="h2 py-3">
                {{ trans.get('images') }} <span class="badge badge-light">{{ countImages }}</span>
            </div>

            <ul class="list-unstyled image-gallery" v-if="hasImages">
                <li class="image-gallery-item" v-for="image in property.images">
                    <property-image-item :image="image"></property-image-item>
                </li>
            </ul>

            <form @submit.prevent="storePropertyImages()" id="propertyImagesForm">
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
                </div>
            </form>

        </div>
        <!-- / Step 4 - Images -->

        <!-- Step 5 - Videos -->
        <div class="shadow p-3 mb-4 rounded">

            <div class="h2 py-3">
                {{ trans.get('videos') }} <span class="badge badge-light">{{ countVideos }}</span>
            </div>

            <ul class="list-unstyled image-gallery" v-if="hasVideos">
                <li class="image-gallery-item" v-for="video in property.videos">
                    <property-video-item :video="video"></property-video-item>
                </li>
            </ul>

            <form @submit.prevent="storePropertyVideos()" id="propertyVideosForm">
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
                </div>
            </form>

        </div>
        <!-- / Step 5 - Videos -->
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import Swal from 'sweetalert2'
    import BaseInput from "../base/BaseInput"
    import BaseInputFile from "../base/BaseInputFile"
    import PropertyService from "../../../services/PropertyService"
    import PropertyImageItem from "../PropertyImageItem";
    import PropertyVideoItem from "../PropertyVideoItem";
    import store from "../../../store/store"

    export default {
        name: "PropertyEditPage",
        components: {PropertyVideoItem, BaseInput, BaseInputFile, PropertyImageItem},
        beforeRouteEnter(routeTo, routeFrom, next) {
            store.dispatch('user/fetchUser', {
                id: routeTo.params.userId
            }).then(() => {
                store.dispatch('user/fetchUserProperty', {
                    propertyId: routeTo.params.propertyId
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
                operationInProgress: false
            }
        },
        methods: {
            updateProperty() {
                PropertyService.updateProperty(this.property.id, this.propertyData).then(() => {
                    this.operationInProgress = false
                    this.successMessage(this.trans.get('project_data_has_been_updated'))
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyForm')
                })
            },
            storePropertyFeatures() {
                PropertyService.storePropertyFeatures(this.propertyData.id, this.propertyFeaturesData).then(response => {
                    this.successMessage(this.trans.get('project_features_has_been_updated'))
                    this.operationInProgress = false
                    this.propertyFeaturesData.id = response.data.data.id
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyFeaturesForm')
                })
            },
            updatePropertyFeatures() {
                PropertyService.updatePropertyFeatures(this.property.id, this.propertyFeaturesData.id, this.propertyFeaturesData).then(() => {
                    this.operationInProgress = false
                    this.successMessage(this.trans.get('project_features_has_been_updated'))
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyFeaturesForm')
                })
            },
            storePropertyLocation() {
                PropertyService.storePropertyLocation(this.propertyData.id, this.propertyLocationData).then(response => {
                    this.operationInProgress = false
                    this.successMessage(this.trans.get('project_location_has_been_updated'))
                    this.propertyLocationData.id = response.data.data.id
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyLocationForm')
                })
            },
            updatePropertyLocation() {
                PropertyService.updatePropertyLocation(this.property.id, this.propertyLocationData.id, this.propertyLocationData).then(() => {
                    this.operationInProgress = false
                    this.successMessage(this.trans.get('project_location_has_been_updated'))
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyLocationForm')
                })
            },
            storePropertyImages() {
                PropertyService.storePropertyImages(this.propertyData.id, this.propertyImagesData).then(response => {
                    this.operationInProgress = false
                    let images = response.data.data
                    this.successMessage(this.trans.get('project_images_has_been_added')).then(() => {
                        if (images && !Object.keys(images).length) {
                            return true
                        }

                        images.forEach(image => {
                            store.commit('user/SET_USER_PROPERTY_IMAGE', image)
                        })

                        delete this.propertyImagesData.images
                    })
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyImagesForm')
                })
            },
            storePropertyVideos() {
                PropertyService.storePropertyVideos(this.propertyData.id, this.propertyVideosData).then(response => {
                    this.operationInProgress = false
                    let videos = response.data.data
                    this.successMessage(this.trans.get('project_videos_has_been_added')).then(() => {
                        if (videos && !Object.keys(videos).length) {
                            return true
                        }

                        videos.forEach(video => {
                            store.commit('user/SET_USER_PROPERTY_VIDEO', video)
                        })

                        this.propertyVideosData = {}
                    })
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#propertyVideosForm')
                })
            },
        },
        created() {
            this.propertyData = {...this.property}
            this.propertyFeaturesData = {...this.property.features}
            this.propertyLocationData = {...this.property.location}
            this.propertyImagesData = {...this.property.images}
            this.propertyVideosData = {...this.property.videos}
        },
        computed: {
            ...mapState({
                user: state => state.user.user,
                property: state => state.user.userProperty
            }),
            hasFeatures() {
                return this.propertyFeaturesData && this.propertyFeaturesData.id
            },
            hasLocation() {
                return this.propertyLocationData && this.propertyLocationData.id
            },
            countImages() {
                return this.hasImages ? Object.keys(this.property.images).length : 0
            },
            hasImages() {
                return this.property.images && Object.keys(this.property.images).length > 0
            },
            countVideos() {
                return this.hasVideos ? Object.keys(this.property.videos).length : 0
            },
            hasVideos() {
                return this.property.videos && Object.keys(this.property.videos).length > 0
            }
         },
    }
</script>

<style scoped>

</style>