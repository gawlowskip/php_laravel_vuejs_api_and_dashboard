<template>
    <div class="project-item" :class="{'blur': operationInProgress}" :id="`agreementItem${agreement.id}`">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <div class="h5">
                    ID #{{ agreement.id }}
                    <span class="badge badge-danger" v-if="agreement.deleted_at">
                        {{ trans.get('cancelled') }}
                    </span>
                </div>
                <span class="text-light">{{ trans.get('created_at') }}</span> {{ agreement.created_at }}
            </div>
            <div class="col-md-4 col-lg-4" v-if="agreement.type === 'regular'">
                <span class="text-light">{{ trans.get('type') }}:</span> {{ agreement.type ?
                capitalize(agreement.type) : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('from_date') }}:</span> {{ agreement.from ?
                agreement.from : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('to_date') }}:</span> {{ agreement.to ?
                agreement.to : trans.get('not_provided') }}
                <br/>
            </div>
            <div class="col-md-4 col-lg-4" v-else-if="agreement.type === 'trial'">
                <span class="text-light">{{ trans.get('type') }}:</span> {{ agreement.type ?
                agreement.type : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('trial_starts_at') }}:</span> {{ agreement.trial_starts_at ?
                agreement.trial_starts_at : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('trial_ends_at') }}:</span> {{ agreement.trial_ends_at ? agreement.trial_ends_at :
                trans.get('not_provided') }}
                <br/>
            </div>
            <div class="col-md-4 col-lg-4" v-if="stripePlan">
                <span class="text-light">{{ trans.get('stripe_plan') }}:</span> {{ stripePlan.metadata.name ?
                stripePlan.metadata.name : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('description') }}:</span> {{ stripePlan.metadata.description ?
                stripePlan.metadata.description : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('price') }}:</span> {{ agreement.price && agreement.currency ?
                `${agreement.price} ${(agreement.currency).toUpperCase()}` : trans.get('not_provided') }}
            </div>
            <div class="col-md-4 col-lg-4" v-else-if="!stripePlan">
                <span class="text-light">{{ trans.get('stripe_plan') }}:</span> {{ agreement.stripe_plan ?
                agreement.stripe_plan : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('stripe_charge') }}:</span> {{ agreement.stripe_charge ?
                agreement.stripe_charge : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('price') }}:</span> {{ agreement.price && agreement.currency ?
                `${agreement.price} ${(agreement.currency).toUpperCase()}` : trans.get('not_provided') }}
            </div>
        </div>
        <div class="row" v-if="agreement.deleted_at">
            <div class="alert alert-primary mt-4" role="alert">
                {{ trans.get('note_user_has_canceled_this_agreement', {'to_date': toDate}) }}
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "AgreementItem",
        props: {
            agreement: {
                type: [Object, Array],
                required: true
            },
        },
        data() {
            return {
                operationInProgress: false,
                stripePlan: null
            }
        },
        methods: {
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1)
            }
        },
        created() {
            if (this.stripePlans && this.agreement.stripe_plan) {
                this.stripePlan = this.stripePlans.filter(stripePlan => stripePlan.id === this.agreement.stripe_plan)[0]
            }
        },
        computed: {
            ...mapState({
                stripePlans: state => state.stripe.stripePlans
            }),
            toDate() {
                if (this.agreement.type === 'regular') {
                    return this.agreement.to
                } else if (this.agreement.type === 'trial') {
                    return this.agreement.trial_ends_at
                }
            }
        }
    }
</script>

<style scoped>

</style>