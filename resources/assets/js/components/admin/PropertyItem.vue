<template>
    <div class="project-item" :class="{'blur': operationInProgress}" :id="`projectItem${property.id}`">
        <div class="row">
            <div class="col-md-4 col-lg-2">
                <div class="h5">ID #{{ property.id }}</div>
                <span class="text-light">{{ trans.get('created_at') }}</span> {{ property.created_at }}
            </div>
            <div class="col-md-4 col-lg-3">
                <span class="text-light">{{ trans.get('description') }}:</span> {{ property.description ?
                property.description : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('bricklayer') }}:</span> {{ property.bricklayer ?
                property.bricklayer : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('carpenter') }}:</span> {{ property.carpenter ?
                property.carpenter : trans.get('not_provided') }}
            </div>
            <div class="col-md-4 col-lg-3">
                <span class="text-light">{{ trans.get('electrician') }}:</span> {{ property.electrician ?
                property.electrician : trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('vvs') }}:</span> {{ property.vvs ? property.vvs :
                trans.get('not_provided') }}
                <br/>
                <span class="text-light">{{ trans.get('entrepreneur') }}:</span> {{ property.entrepreneur ?
                property.entrepreneur : trans.get('not_provided') }}
            </div>
            <div class="col-md-8 offset-md-4 mt-md-3 offset-lg-0 col-lg-4 mt-lg-0 btn-action">
                <router-link
                        :to="{name: 'PropertyEditPage', params: {'userId': property.developer.id, 'propertyId': property.id}}"
                        tag="button"
                        class="btn btn-outline-dark" v-if="showEditButton && property.developer">
                    <i class="fas fa-pencil-alt"></i> {{ trans.get('edit') }}
                </router-link>
                <router-link
                        :to="{name: 'PropertyDetailsPage', params: {'userId': property.developer.id, 'propertyId': property.id}}"
                        tag="button"
                        class="btn btn-outline-dark" v-if="showDetailsButton && property.developer">
                    <i class="fas fa-long-arrow-alt-right"></i> {{ trans.get('details') }}
                </router-link>
                <div class="d-inline ml-2" v-if="showDestroyButton">
                    <button type="button" class="btn btn-circle" @click="deleteProperty()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PropertyService from "../../services/PropertyService";

    export default {
        name: "PropertyItem",
        props: {
            property: {
                type: [Object, Array],
                required: true
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
                operationInProgress: false,
            }
        },
        methods: {
            deleteProperty() {
                this.operationInProgress = true

                this.confirmMessage(this.trans.get('are_you_sure_you_want_to_remove_this_project')).then((result) => {
                    if (result.value) {
                        PropertyService.destroyProperty(this.property.id).then(() => {
                            this.successMessage(this.trans.get('project_has_been_removed')).then(() => {
                                this.$emit('deletePropertyFromList', this.property.id)
                                this.operationInProgress = false
                            })
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                            this.operationInProgress = false
                        })
                    } else {
                        this.cancelAnimation(`#projectItem${this.property.id}`)
                        this.operationInProgress = false
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>