<template>
    <tr>
        <td :data-th="trans.get('user_id')">{{ lead.user_id }}</td>
        <td :data-th="trans.get('full_name')">{{ lead.full_name }}</td>
        <td :data-th="trans.get('email')">
            <a :href="`mailto:${lead.email}`" v-if="lead.email">{{ lead.email }}</a>
            <span v-else-if="!lead.email">{{ trans.get('not_provided') }}</span>
        </td>
        <td :data-th="trans.get('clicked_on')">{{ lead.clicked_on }}</td>
        <td :data-th="trans.get('latitude')">{{ lead.latitude }}</td>
        <td :data-th="trans.get('longitude')">{{ lead.longitude }}</td>
        <td :data-th="trans.get('created_at')">{{ lead.created_at }}</td>
    </tr>
</template>

<script>
    export default {
        name: "AdLeadsTableRow",
        props: {
            lead: {
                type: [Object],
                required: true
            }
        }
    }
</script>

<style scoped>

</style>