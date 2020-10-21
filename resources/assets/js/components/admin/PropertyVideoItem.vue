<template>
    <figure class="figure" :class="{'blur': operationInProgress}" :id="`videoItem${video.id}`">
        <video width="320" height="240" controls>
            <source :src="video.filename" type="video/mp4">
        </video>
        <figcaption class="figure-caption text-center" v-if="showDestroyButton">
            <button type="button" class="btn btn-danger btn-sm" @click="destroyPropertyVideo()">
                {{ trans.get('remove') }}
            </button>
        </figcaption>
    </figure>
</template>

<script>
    import PropertyService from "../../services/PropertyService"
    import store from "../../store/store";

    export default {
        name: "PropertyVideoItem",
        props: {
            video: {
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
            destroyPropertyVideo() {
                this.confirmMessage(this.trans.get('are_you_sure_you_want_to_remove_this_video')).then((result) => {
                    if (result.value) {
                        PropertyService.destroyPropertyVideo(this.video.property_id, this.video.id).then(() => {
                            this.successMessage(this.trans.get('video_has_been_removed')).then(() => {
                                store.commit('user/REMOVE_USER_PROPERTY_VIDEO', this.video.id)
                                this.operationInProgress = false
                            })
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                            this.operationInProgress = false
                        })
                    } else {
                        this.cancelAnimation(`#videoItem${this.video.id}`)
                        this.operationInProgress = false
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>