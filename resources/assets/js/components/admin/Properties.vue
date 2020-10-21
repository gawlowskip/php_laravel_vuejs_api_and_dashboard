<template>
    <div class="project-list">
        <property-item :property="property" v-for="property in properties" :key="property.id"
                       @deletePropertyFromList="deletePropertyFromList"></property-item>
    </div>
</template>

<script>
    import store from "../../store/store";
    import {mapState} from 'vuex'
    import PropertyItem from "./PropertyItem";
    import AxiosService from "../../services/AxiosService";

    export default {
        name: "Properties",
        components: {PropertyItem},
        props: {
            userId: {
                required: true
            }
        },
        methods: {
            deletePropertyFromList(propertyId) {
                let data = this.properties.filter(property => property.id !== propertyId)
                let meta = null

                this.$store.commit('developer/SET_DEVELOPER_PROPERTIES_DATA', AxiosService.rebuildResponse(data, meta))
            }
        },
        created() {
            const page = 1
            store.dispatch('developer/fetchDeveloperProperties', {
                developerId: this.userId,
                page: page
            })
        },
        computed: {
            ...mapState({
                properties: state => state.developer.developerProperties
            })
        },
    }
</script>

<style scoped>

</style>