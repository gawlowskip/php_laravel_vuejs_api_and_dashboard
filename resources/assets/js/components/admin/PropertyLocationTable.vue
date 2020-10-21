<template>
    <table class="table table-hover rwd-table">
        <thead class="thead-dark">
        <tr>
            <th>{{ trans.get('district') }}</th>
            <th>{{ trans.get('city') }}</th>
            <th>{{ trans.get('street') }}</th>
            <th>{{ trans.get('latitude') }}</th>
            <th>{{ trans.get('longitude') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td :data-th="trans.get('district')">{{ location.district ? location.district : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('city')">{{ location.city ? location.city : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('street')">{{ location.street ? location.street : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('latitude')">{{ location.latitude ? location.latitude : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('longitude')">{{ location.longitude ? location.longitude : trans.get('not_provided') }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "PropertyLocationTable",
        props: {
            location: {
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>