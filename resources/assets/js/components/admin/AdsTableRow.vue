<template>
    <tr :class="{'blur': operationInProgress}" :id="`adItem${ad.id}`">
        <td>
            <div class="h5">ID #{{ ad.id }}
                <span class="badge badge-success" v-if="ad.active">{{ trans.get('active') }}</span>
                <span class="badge badge-secondary" v-else-if="!ad.active">{{ trans.get('inactive') }}</span>
            </div>
            <span class="text-light">{{ trans.get('from_date') }}:</span> {{ ad.from_date }}
            <br/>
            <span class="text-light">{{ trans.get('to_date') }}:</span> {{ ad.to_date }}
            <br/>
            <span class="text-light">{{ trans.get('areas') }}:</span>
            <span v-if="hasAreas">
                <span v-for="area in ad.areas">
                    {{ area.name }}
                </span>
            </span>
            <span v-else-if="!hasAreas">{{ trans.get('not_provided')}}</span>
        </td>
        <td :data-th="trans.get('price')">{{ ad.price }}</td>
        <td :data-th="trans.get('price_lead')">{{ ad.price_lead }}</td>
        <td :data-th="trans.get('seconds')">{{ ad.seconds }}</td>
        <td :data-th="trans.get('image')">
            <img :src="ad.image" class="img-thumbnail custom-image-sm" v-if="ad.image">
            <span v-if="!ad.image">{{ trans.get('not_included') }}</span>
        </td>
        <td :data-th="trans.get('leads')">
            <button type="button" class="btn btn-sm btn-outline-dark" @click.prevent="showAdLeadsModal()"
                    :disabled="!hasLeads">
                {{ trans.get('show') }} <span class="badge badge-pill badge-light">{{ (ad.leads).length }}</span>
            </button>
        </td>
        <td :data-th="trans.get('manage')">
            <div class="btn-action text-right">
                <router-link :to="{name: 'AdEditPage', params: {'userId': ad.developer_id, 'adId': ad.id}}"
                             tag="button"
                             class="btn btn-sm btn-outline-dark">
                    <i class="fas fa-pencil-alt"></i> {{ trans.get('edit') }}
                </router-link>
                <div class="d-inline">
                    <button type="button" class="btn btn-sm btn-circle" @click="deleteAd()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
    import DeveloperService from "../../services/DeveloperService";

    export default {
        name: "AdRow",
        props: {
            ad: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                operationInProgress: false,
            }
        },
        methods: {
            showAdLeadsModal() {
                if (!this.hasLeads) {
                    return true
                }

                this.$root.$emit('showAdLeadsModal', this.ad)
            },
            deleteAd() {
                this.operationInProgress = true

                this.confirmMessage(this.trans.get('are_you_sure_you_want_to_remove_this_ad')).then((result) => {
                    if (result.value) {
                        DeveloperService.destroyDeveloperAd(this.ad.developer_id, this.ad.id).then(() => {
                            this.successMessage(this.trans.get('ad_has_been_removed')).then(() => {
                                this.$emit('deleteAdFromList', this.ad.id)
                                this.operationInProgress = false
                            })
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                            this.operationInProgress = false
                        })
                    } else {
                        this.cancelAnimation(`#adItem${this.ad.id}`)
                        this.operationInProgress = false
                    }
                })
            }
        },
        computed: {
            hasAreas() {
                return this.ad.areas && Object.keys(this.ad.areas).length > 0
            },
            hasLeads() {
                return this.ad.leads && Object.keys(this.ad.leads).length > 0
            }
        }
    }
</script>

<style scoped>
    .custom-image-sm {
        width: 100px;
        height: 100px;
    }
</style>