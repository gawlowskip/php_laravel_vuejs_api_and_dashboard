<template>
    <nav class="mt-4">
        <ul class="pagination justify-content-center" v-if="meta && (meta.last_page && meta.last_page > 1)">
            <li class="page-item" :class="{'disabled': meta.current_page === 1}">
                <button type="button" class="page-link" :disabled="meta.current_page === 1"
                        @click="changePage(meta.current_page - 1)">
                    {{ trans.get('previous') }}
                </button>
            </li>
            <li class="page-item" :class="{'active': meta.current_page === n}" v-for="n in meta.last_page" :key="n">
                <button type="button" class="page-link" @click="changePage(n)">{{ n }}</button>
            </li>
            <li class="page-item" :class="{'disabled': meta.current_page === meta.last_page}">
                <button type="button" class="page-link" :disabled="meta.current_page === meta.last_page"
                        @click="changePage(meta.current_page + 1)">
                    {{ trans.get('next') }}
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    import store from "../../store/store";
    import {mapState} from "vuex";

    export default {
        name: "Pagination",
        props: {
            meta: {
                required: false,
                type: Object
            },
            componentName: {
                required: false,
                type: String
            }
        },
        methods: {
            changePage(pageNumber) {
                let query = {
                    query: {
                        page: pageNumber
                    }
                }

                switch (this.componentName) {
                    case 'UsersPage':
                        store.dispatch('user/fetchUsers', {
                            page: pageNumber
                        }).then(() => {
                            this.$router.replace(query)
                            scrollTo({
                                top: 0,
                                left: 0,
                                behavior: 'smooth'
                            });
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                        })
                        break
                    case 'AdsTable':
                        store.dispatch('developer/fetchDeveloperAds', {
                            developerId: this.user.id,
                            page: pageNumber
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                        })
                        break
                    case 'Properties':
                        store.dispatch('developer/fetchDeveloperProperties', {
                            developerId: this.user.id,
                            page: pageNumber
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage()
                        })
                        break
                    case 'AdLeadsTable':
                        store.dispatch('ad/fetchAdLeads', {
                            adId: this.ad.id,
                            page: pageNumber
                        }).catch(error => {
                            this.errors = error.response.data.error
                            this.errorMessage().then(() => {
                                $('#adLeadsModal').modal('hide')
                            })
                        })
                        break
                    default:
                        break
                }
            }
        },
        computed: {
            ...mapState({
                user: state => state.user.user,
                ad: state => state.ad.ad
            }),
        }
    }
</script>

<style scoped>

</style>