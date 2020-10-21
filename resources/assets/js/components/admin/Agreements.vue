<template>
    <div class="project-list">
        <agreement-item :agreement="agreement" v-for="agreement in agreements" :key="agreement.id"></agreement-item>
    </div>
</template>

<script>
    import AgreementItem from "./AgreementItem";
    import store from "../../store/store";
    import {mapState} from "vuex";
    export default {
        name: "Agreements",
        components: {AgreementItem},
        props: {
            developerId: {
                required: true
            }
        },
        created() {
            store.dispatch('stripe/fetchPlans').then(() => {
                store.dispatch('developer/fetchDeveloperAgreements', {
                    developerId: this.developerId
                })
            })
        },
        computed: {
            ...mapState({
                agreements: state => state.developer.developerAgreements
            })
        }
    }
</script>

<style scoped>

</style>