<template>
    <table class="table table-sm table-hover rwd-table">
        <thead class="thead-light">
        <tr>
            <th>{{ trans.get('user_id') }}</th>
            <th>{{ trans.get('full_name') }}</th>
            <th>{{ trans.get('email') }}</th>
            <th>{{ trans.get('clicked_on') }}</th>
            <th>{{ trans.get('latitude') }}</th>
            <th>{{ trans.get('longitude') }}</th>
            <th>{{ trans.get('created_at') }}</th>
        </tr>
        </thead>
        <tbody>
            <ad-leads-table-row :lead="lead" v-for="lead in leads" :key="lead.id"></ad-leads-table-row>
        </tbody>
    </table>
</template>

<script>
    import AdLeadsTableRow from "./AdLeadsTableRow";

    export default {
        name: "AdLeadsTable",
        components: {AdLeadsTableRow},
        props: {
            leads: {
                type: [Array, Object],
                required: true
            }
        }
    }
</script>

<style scoped>

</style>