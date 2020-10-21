<template>
    <div class="container">

        <div class="content-title py-4 d-flex justify-content-between align-items-center">
            <div class="btn-toolbar ml-auto">
                <router-link :to="{name: 'UsersPage'}" tag="a" class="btn btn-outline-dark ml-auto" v-if="step === 1">
                    <i class="fas fa-long-arrow-alt-left"></i>{{ trans.get('back') }}
                </router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card" :class="{'border-dark': step === 1}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step === 1"></i>
                            <i class="fas fa-check-square" v-else-if="step > 1"></i>
                            {{ trans.get('step', {'step_no': 1}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('create_developer') }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card" :class="{'border-dark': step === 2}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="far fa-check-square" v-if="step <= 2"></i>
                            <i class="fas fa-check-square" v-else-if="step > 2"></i>
                            {{ trans.get('step', {'step_no': 2}) }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ trans.get('add_agreement') }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 1 -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storeUser()" v-if="step === 1" id="userForm">

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
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">{{
                    trans.get('create') }}
                </button>
            </div>

        </form>
        <!-- / Step 1 -->

        <!-- Step 2 -->
        <form class="shadow p-3 mb-4 rounded" @submit.prevent="storeUserAgreement()" v-if="step === 2" id="agreementForm">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <base-select id="stripe_plan" :label="trans.get('agreement')" :options="userForm.stripePlanOptions"
                                 :errorsData=errors v-model="userAgreementData.stripe_plan"></base-select>
                </div>
                <div class="form-group col-md-6">
                    <base-select id="type" :label="trans.get('agreement_type')" :options="userForm.agreementTypeOptions"
                                 :errorsData=errors v-model="userAgreementData.type"></base-select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6"
                     v-if="userAgreementData.type && userAgreementData.type.value === 'trial'">
                    <base-input id="trial_starts_at" type="date" :label="trans.get('trial_starts_at')"
                                :errorsData=errors v-model="userAgreementData.trial_starts_at"></base-input>
                </div>
                <div class="form-group col-md-6"
                     v-if="userAgreementData.type && userAgreementData.type.value === 'trial'">
                    <base-input id="trial_ends_at" type="date" :label="trans.get('trial_ends_at')" :errorsData=errors
                                v-model="userAgreementData.trial_ends_at"></base-input>
                </div>
            </div>

            <div class="d-flex border-top py-4">
                <button type="submit" class="btn btn-primary" name="button" :disabled="operationInProgress">{{
                    trans.get('add_agreement') }}
                </button>
                <button type="button" class="btn btn-outline-dark ml-2" name="button" @click="skipStoreUserAgreement()"
                        :disabled="operationInProgress">{{ trans.get('skip') }}
                </button>
            </div>
        </form>
        <!-- / Step 2 -->

    </div>
</template>

<script>
    import BaseInput from "../base/BaseInput";
    import UserService from "../../../services/UserService";
    import BaseSelect from "../base/BaseSelect";
    import store from "../../../store/store";
    import {mapState} from 'vuex'
    import {formMixin} from "../../../mixins/formMixin";

    export default {
        name: "UserCreatePage",
        components: {BaseSelect, BaseInput},
        mixins: [formMixin],
        beforeRouteEnter(routeTo, routeFrom, next) {
            store.dispatch('stripe/fetchPlans').then(() => {
                next()
            })
        },
        data() {
            return {
                userData: {},
                userAgreementData: {},
                step: 1,
                operationInProgress: false
            }
        },
        computed: {
            ...mapState({
                stripePlans: state => state.stripe.stripePlans
            })
        },
        methods: {
            storeUser() {
                this.operationInProgress = true

                UserService.storeUser(this.userData).then(response => {
                    this.userData.id = response.data.data.id
                    this.step = 2
                    this.operationInProgress = false
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#userForm')
                })
            },
            storeUserAgreement() {
                this.operationInProgress = true
                UserService.storeUserAgreement(this.userData.id, this.userAgreementData).then(() => {
                    this.finish()
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.operationInProgress = false
                    this.validationAnimation('#agreementForm')
                })
            },
            skipStoreUserAgreement() {
                this.finish()
            },
            finish() {
                this.step = 3
                this.successMessage(this.trans.get('developer_has_been_created', {
                    'first_name': this.userData.name,
                    'last_name': this.userData.last_name
                })).then(() => {
                    this.operationInProgress = false
                    this.$router.push({
                        name: 'UsersPage'
                    })
                })
            }
        },
    }
</script>

<style scoped>

</style>