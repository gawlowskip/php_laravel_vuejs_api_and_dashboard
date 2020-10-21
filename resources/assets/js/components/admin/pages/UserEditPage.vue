<template>
    <div class="container">

        <div class="content-title py-4 d-flex justify-content-between align-items-center">
            <div class="btn-toolbar ml-auto">
                <router-link :to="{name: 'UsersPage'}" tag="a" class="btn btn-outline-dark ml-auto">
                    <i class="fas fa-long-arrow-alt-left"></i>{{ trans.get('back') }}
                </router-link>
            </div>
        </div>

        <!-- User data -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="updateUser()" id="userForm">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="name" :label="trans.get('first_name')" :errorsData=errors
                                v-model="userData.name"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="last_name" :label="trans.get('last_name')" :errorsData=errors
                                v-model="userData.last_name"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="email" :label="trans.get('email')" :errorsData=errors
                                v-model="userData.email"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="password" :label="trans.get('password')" type="password" :errorsData=errors
                                v-model="userData.password"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="phone" :label="trans.get('phone')" :errorsData=errors
                                v-model="userData.phone"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="facebook_id" :label="trans.get('facebook_id')" :errorsData=errors
                                v-model="userData.facebook_id"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="architect_name" :label="trans.get('architect_name')" :errorsData=errors
                                v-model="userData.architect_name"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="cvr_number" :label="trans.get('cvr_number')" :errorsData=errors
                                v-model="userData.cvr_number"></base-input>
                </div>
            </div>

            <hr/>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="street_1" :label="trans.get('street_1')" :errorsData=errors
                                v-model="userData.street_1"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="street_2" :label="trans.get('street_2')" :errorsData=errors
                                v-model="userData.street_2"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="city" :label="trans.get('city')" :errorsData=errors
                                v-model="userData.city"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="postal_code" :label="trans.get('postal_code')" :errorsData=errors
                                v-model="userData.postal_code"></base-input>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-input id="latitude" :label="trans.get('latitude')" :errorsData=errors
                                v-model="userData.latitude"></base-input>
                </div>
                <div class="form-group col-md-6">
                    <base-input id="longitude" :label="trans.get('longitude')" :errorsData=errors
                                v-model="userData.longitude"></base-input>
                </div>
            </div>

            <hr/>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-select id="active" :label="trans.get('active')" :options="userForm.activeOptions"
                                 :errorsData=errors v-model="userData.active"></base-select>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ trans.get('update_developer_data') }}
                </button>
            </div>

        </form>
        <!-- / User data -->

        <!-- User Agreement data -->
        <form class="shadow p-3 mb-4 rounded"
              @submit.prevent="hasAgreement ? updateDeveloperAgreement() : storeUserAgreement()" id="agreementForm">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-select id="stripe_plan" :label="trans.get('agreement')" :options="userForm.stripePlanOptions"
                                 :errorsData=errors v-model="developerAgreementData.stripe_plan"></base-select>
                </div>
                <div class="form-group col-md-6">
                    <base-select id="type" :label="trans.get('agreement_type')" :options="userForm.agreementTypeOptions"
                                 :errorsData=errors v-model="developerAgreementData.type"></base-select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6"
                     v-if="developerAgreementData.type && developerAgreementData.type.value === 'trial'">
                    <base-input id="trial_starts_at" type="date" :label="trans.get('trial_starts_at')"
                                :errorsData=errors v-model="developerAgreementData.trial_starts_at"></base-input>
                </div>
                <div class="form-group col-md-6"
                     v-if="developerAgreementData.type && developerAgreementData.type.value === 'trial'">
                    <base-input id="trial_ends_at" type="date" :label="trans.get('trial_ends_at')" :errorsData=errors
                                v-model="developerAgreementData.trial_ends_at"></base-input>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">
                    {{ hasAgreement ? trans.get('update_agreement_data') : trans.get('add_agreement') }}
                </button>

                <button type="button" class="btn btn-danger ml-2" @click="destroyDeveloperAgreement()" v-if="hasAgreement">
                    {{ trans.get('remove') }}
                </button>
            </div>
        </form>
        <!-- / User Agreement data -->

    </div>
</template>

<script>
    import BaseInput from "../base/BaseInput";
    import UserService from "../../../services/UserService";
    import BaseSelect from "../base/BaseSelect";
    import store from "../../../store/store";
    import {mapState} from 'vuex'
    import {formMixin} from "../../../mixins/formMixin";
    import Swal from 'sweetalert2'
    import DeveloperService from "../../../services/DeveloperService";

    export default {
        name: "UserEditPage",
        components: {BaseSelect, BaseInput},
        mixins: [formMixin],
        beforeRouteEnter(routeTo, routeFrom, next) {
            store.dispatch('user/fetchUser', {
                id: routeTo.params.id
            }).then(() => {
                store.dispatch('developer/fetchDeveloperAgreements', {
                    developerId: routeTo.params.id
                }).then(() => {
                    store.dispatch('stripe/fetchPlans').then(() => {
                        next()
                    }).catch(() => {
                        next()
                    })
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
                userData: {},
                developerAgreementData: {},
                operationInProgress: false
            }
        },
        methods: {
            updateUser() {
                this.operationInProgress = true

                UserService.updateUser(this.userData, this.userData.id).then(() => {
                    this.operationInProgress = false

                    this.successMessage(this.trans.get('developer_data_has_been_updated', {
                        'first_name': this.userData.name,
                        'last_name': this.userData.last_name
                    }))
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#userForm')
                })
            },
            parseDeveloperAgreementData() {
                if (Object.keys(this.developerAgreementData).length) {
                    if (this.developerAgreementData[0]) {
                        this.developerAgreementData = this.developerAgreementData[0]
                    }

                    this.developerAgreementData.stripe_plan = {
                        ...(this.userForm.stripePlanOptions).filter(option => {
                            return option.value === this.developerAgreementData.stripe_plan
                        })[0]
                    }

                    this.developerAgreementData.type = {
                        ...(this.userForm.agreementTypeOptions).filter(option => {
                            return option.value === this.developerAgreementData.type
                        })[0]
                    }

                    if (this.developerAgreementData.trial_starts_at) {
                        this.developerAgreementData.trial_starts_at = moment(this.developerAgreementData.trial_starts_at).format('YYYY-MM-DD')
                    }

                    if (this.developerAgreementData.trial_ends_at) {
                        this.developerAgreementData.trial_ends_at = moment(this.developerAgreementData.trial_ends_at).format('YYYY-MM-DD')
                    }
                }
            },
            storeUserAgreement() {
                this.operationInProgress = true
                UserService.storeUserAgreement(this.userData.id, this.developerAgreementData).then((response) => {
                    this.successMessage(this.trans.get('agreement_data_has_been_created')).then(() => {
                        this.developerAgreementData = response.data.data
                        this.parseDeveloperAgreementData()
                        this.operationInProgress = false
                    })
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#agreementForm')
                })
            },
            updateDeveloperAgreement() {
                this.operationInProgress = true
                DeveloperService.updateDeveloperAgreement(this.userData.id, this.developerAgreementData.id, this.developerAgreementData).then(() => {
                    this.successMessage(this.trans.get('agreement_data_has_been_updated'))
                    this.operationInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#agreementForm')
                })
            },
            destroyDeveloperAgreement() {
                this.operationInProgress = true

                this.confirmMessage(this.trans.get('are_you_sure_you_want_to_remove_this_agreement')).then((result) => {
                    if (result.value) {
                        DeveloperService.destroyDeveloperAgreement(this.userData.id, this.developerAgreementData.id).then(() => {
                            this.successMessage(this.trans.get('agreement_has_been_deleted')).then(() => {
                                this.developerAgreementData = {
                                    type: null,
                                    stripe_plan: null,
                                    trials_starts_at: null,
                                    trials_ends_at: null
                                }
                                this.operationInProgress = false
                            })
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                            this.operationInProgress = false
                            this.validationAnimation('#agreementForm')
                        })
                    } else {
                        this.operationInProgress = false
                        this.cancelAnimation('#agreementForm')
                    }
                })
            }
        },
        created() {
            this.userData = {...this.user}
            this.developerAgreementData = {...this.developerAgreements}

            this.userData.active = {
                ...(this.userForm.activeOptions).filter(option => {
                    return parseInt(option.value) === parseInt(this.userData.active)
                })[0]
            }

            this.parseDeveloperAgreementData()
        },
        computed: {
            ...mapState({
                user: state => state.user.user,
                developerAgreements: state => state.developer.developerAgreements,
                stripePlans: state => state.stripe.stripePlans
            }),
            hasAgreement() {
                return Object.keys(this.developerAgreementData).length && this.developerAgreementData.id
            }
        },
    }
</script>

<style scoped>

</style>