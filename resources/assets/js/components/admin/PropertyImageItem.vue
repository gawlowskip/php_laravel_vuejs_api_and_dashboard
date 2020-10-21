<template>
    <figure class="figure" :class="{'blur': operationInProgress}" :id="`imageItem${image.id}`">
        <img :src="image.filename" class="figure-img img-thumbnail" :alt="image.filename">
        <figcaption class="figure-caption text-center" v-if="showDestroyButton">
            <button type="button" class="btn btn-danger btn-sm" @click="destroyPropertyImage()">
                {{ trans.get('remove') }}
            </button>
        </figcaption>
    </figure>
</template>

<script>
    import PropertyService from "../../services/PropertyService"
    import store from "../../store/store";

    export default {
        name: "PropertyImageItem",
        props: {
            image: {
                required: true,
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
            destroyPropertyImage() {
                this.operationInProgress = true

                this.confirmMessage(this.trans.get('are_you_sure_you_want_to_remove_this_image')).then((result) => {
                    if (result.value) {
                        PropertyService.destroyPropertyImage(this.image.property_id, this.image.id).then(() => {
                            this.successMessage(this.trans.get('image_has_been_removed')).then(() => {
                                store.commit('user/REMOVE_USER_PROPERTY_IMAGE', this.image.id)
                                this.operationInProgress = false
                            })
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                            this.operationInProgress = false
                        })
                    } else {
                        this.cancelAnimation(`#imageItem${this.image.id}`)
                        this.operationInProgress = false
                    }
                })
            },
        }
    }
</script>

<style scoped>

</style>