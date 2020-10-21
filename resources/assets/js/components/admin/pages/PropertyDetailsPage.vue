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
            <user-item :user="user" :showPropertiesLabel="false" :showAdsLabel="false" :showEditButton="false" :showDetailsButton="false"
                       :showDestroyButton="false">
            </user-item>
        </div>

        <div class="shadow p-3 mb-4 rounded">
            <div class="h2 py-3">
                {{ trans.get('project') }}
            </div>
            <div class="project-list">
                <property-item :property="property" :showDetailsButton="false"
                               :showDestroyButton="false"></property-item>
            </div>
        </div>

        <div class="shadow p-3 mb-4 rounded">
            <div class="h2 py-3">{{ trans.get('features') }}</div>
            <property-features-table :features="hasFeatures ? property.features : {}"></property-features-table>
        </div>

        <div class="shadow p-3 mb-4 rounded">
            <div class="h2 py-3">{{ trans.get('location') }}</div>
            <property-location-table :location="hasLocation ? property.location : {}"></property-location-table>
        </div>

        <div class="shadow p-3 mb-4 rounded">
            <div class="h2 py-3">
                {{ trans.get('images') }} <span class="badge badge-light">{{ countImages }}</span>
            </div>

            <ul class="list-unstyled image-gallery" v-if="hasImages">
                <li class="image-gallery-item" v-for="image in property.images">
                    <property-image-item :image="image" :showDestroyButton="false"></property-image-item>
                </li>
            </ul>
        </div>

        <div class="shadow p-3 mb-4 rounded">
            <div class="h2 py-3">
                {{ trans.get('videos') }} <span class="badge badge-light">{{ countVideos }}</span>
            </div>

            <ul class="list-unstyled image-gallery" v-if="hasVideos">
                <li class="image-gallery-item" v-for="video in property.videos">
                    <property-video-item :video="video" :showDestroyButton="false"></property-video-item>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import Swal from 'sweetalert2'
    import store from "../../../store/store"
    import UserItem from "../UserItem";
    import PropertyItem from "../PropertyItem";
    import PropertyFeaturesTable from "../PropertyFeaturesTable"
    import PropertyLocationTable from "../PropertyLocationTable";
    import PropertyImageItem from "../PropertyImageItem";
    import PropertyVideoItem from "../PropertyVideoItem";

    export default {
        name: "PropertyDetailsPage",
        components: {UserItem, PropertyItem, PropertyFeaturesTable, PropertyLocationTable, PropertyImageItem, PropertyVideoItem},
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
        computed: {
            ...mapState({
                user: state => state.user.user,
                property: state => state.user.userProperty
            }),
            hasFeatures() {
                return this.property.features && Object.keys(this.property.features).length > 0
            },
            hasLocation() {
                return this.property.location && Object.keys(this.property.location).length > 0
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
        }
    }
</script>

<style scoped>

</style>