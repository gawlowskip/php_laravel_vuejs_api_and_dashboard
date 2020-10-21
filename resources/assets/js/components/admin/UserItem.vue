<template>
    <div :id="`userItem${user.id}`" class="developers-list-item" :class="{'blur': operationInProgress}">
        <div class="h4">
            {{ user.name }} {{ user.last_name }}
            <span class="badge badge-secondary">{{ trans.get('id') }}: {{ user.id }}</span>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4">
                        <a :href="`mailto:${user.email}`"><i class="fas fa-envelope"></i>{{ user.email }}</a>
                        <br/>
                        <a :href="`tel: ${user.phone}`">
                            <i class="fas fa-phone"></i>{{ user.phone ? user.phone : trans.get('not_provided') }}
                        </a>
                        <br/>
                        <i class="fab fa-facebook-f"></i> {{ user.facebook_id ? user.facebook_id : trans.get('not_provided') }}
                    </div>
                    <div class="col-lg-4">
                        <i class="fas fa-globe ic-pos-left"></i>
                        <span v-if="hasCoordinates">
                            {{ user.latitude }}, {{ user.longitude }}
                        </span>
                        <span v-else-if="!hasCoordinates">
                            {{ trans.get('not_provided') }}
                        </span>
                        <br/>
                        <i class="fas fa-map-marker-alt ic-pos-left"></i>
                        <span v-if="hasAddress">
                            {{ user.street_1 }} {{ user.street_2 }}<br/>
                            {{ user.postal_code }}, {{ user.city }}
                        </span>
                        <span v-else-if="!hasAddress">
                            {{ trans.get('not_provided') }}
                        </span>
                    </div>
                    <div class="col-lg-4">
                        <span class="text-light">{{ trans.get('architect_name') }}</span>
                        {{ user.architect_name ? user.architect_name : trans.get('not_provided') }}
                        <br/>
                        <span class="text-light">{{ trans.get('cvr') }}</span>
                        {{ user.cvr_number ? user.cvr_number : trans.get('not_provided') }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-4">
                        <i class="fas" :class="{'fa-check': user.active, 'fa-minus': !user.active}"></i> <span class="text-light">{{ trans.get('active') }}</span>
                    </div>
                    <div class="col-lg-4">
                        <i class="fas" :class="{'fa-check': user.is_developer, 'fa-minus': !user.is_developer}"></i> <span class="text-light">{{ trans.get('developer') }}</span>
                    </div>
                    <div class="col-lg-4">
                        <i class="fas" :class="{'fa-check': user.is_active_developer, 'fa-minus': !user.is_active_developer}"></i> <span class="text-light">{{ trans.get('agreement') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 btn-action">
                <span class="badge mr-1" :class="{'badge-light': !user.count_properties, 'badge-dark': user.count_properties}" v-if="showPropertiesLabel">{{ trans.get('projects') }}: {{ user.count_properties }}</span>
                <span class="badge mr-1" :class="{'badge-light': !user.count_ads, 'badge-dark': user.count_ads}" v-if="showAdsLabel">{{ trans.get('ads') }}: {{ user.count_ads }}</span>

                <div class="btn-group">
                    <router-link :to="{name: 'UserDetailsPage', params: {'id': user.id}}" tag="button"
                                 class="btn btn-sm btn-outline-dark ml-2y" v-if="showDetailsButton">
                        <i class="fas fa-long-arrow-alt-right"></i> {{ trans.get('details') }}
                    </router-link>
                    <button type="button" class="btn btn-sm btn-outline-dark ml-2y dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">
                        <h6 class="dropdown-header">{{ trans.get('manage') }}</h6>
                        <router-link :to="{name: 'UserDetailsPage', params: {'id': user.id}}" tag="button"
                                     class="dropdown-item" v-if="showDetailsButton">
                            <i class="fas fa-long-arrow-alt-right"></i> {{ trans.get('details') }}
                        </router-link>
                        <router-link :to="{name: 'UserEditPage', params: {'id': user.id}}" tag="button"
                                     class="dropdown-item" v-if="showEditButton">
                            <i class="fas fa-pencil-alt"></i> {{ trans.get('edit') }}
                        </router-link>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" @click="deleteUser(user)">
                            <i class="fas fa-times"></i> {{ trans.get('delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {authComputed} from "../../store/helpers";
    import UserService from "../../services/UserService";

    export default {
        name: "UserItem",
        props: {
            user: {
                type: Object,
                required: true
            },
            showPropertiesLabel: {
                type: Boolean,
                default: true
            },
            showAdsLabel: {
                type: Boolean,
                default: true
            },
            showDetailsButton: {
                type: Boolean,
                default: true
            },
            showEditButton: {
                type: Boolean,
                default: true
            },
            showDestroyButton: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                operationInProgress: false
            }
        },
        methods: {
            deleteUser(user) {
                if (user.id === this.authId) {
                    this.validationAnimation(`#userItem${this.user.id}`)
                    return false
                }

                this.operationInProgress = true

                this.confirmMessage(this.trans.get('are_you_sure_you_want_to_remove_developer', {
                    'first_name': user.name,
                    'last_name': user.last_name
                })).then((result) => {
                    if (result.value) {
                        UserService.destroyUser(user.id).then(() => {
                            this.successMessage(this.trans.get('developer_has_been_removed', {
                                'first_name': user.name,
                                'last_name': user.last_name
                            })).then(() => {
                                this.$emit('deleteUserFromList', user.id)
                                this.operationInProgress = false
                            })
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                        })
                    } else {
                        this.cancelAnimation(`#userItem${this.user.id}`)
                        this.operationInProgress = false
                    }
                })
            }
        },
        computed: {
            ...authComputed,
            hasAddress() {
                return (this.user.street_1 || this.user.street_2) || (this.user.postal_code && this.user.city)
            },
            hasCoordinates() {
                return this.user.latitude && this.user.longitude
            }
        },
    }
</script>

<style scoped>

</style>