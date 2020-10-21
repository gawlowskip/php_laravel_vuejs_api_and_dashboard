<template>
    <table class="table table-hover rwd-table">
        <thead class="thead-dark">
        <tr>
            <th>{{ trans.get('property_type') }}</th>
            <th>{{ trans.get('material') }}</th>
            <th>{{ trans.get('completion_date') }}</th>
            <th>{{ trans.get('size') }}</th>
            <th>{{ trans.get('rooms') }}</th>
            <th>{{ trans.get('baths') }}</th>
            <th>{{ trans.get('bedrooms') }}</th>
            <th>{{ trans.get('floors') }}</th>
            <th>{{ trans.get('price') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td :data-th="trans.get('property_type')">{{ features.property_type ? features.property_type : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('material')">{{ features.material ? features.material : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('completion_date')">{{ features.completion_date ? features.completion_date : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('size')">{{ features.size ? features.size : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('rooms')">{{ features.rooms_amount ? features.rooms_amount : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('baths')">{{ features.baths_amount ? features.baths_amount : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('bedrooms')">{{ features.bedrooms_amount ? features.bedrooms_amount : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('floors')">{{ features.floors ? features.floors : trans.get('not_provided') }}</td>
            <td :data-th="trans.get('price')">{{ features.price ? features.price : trans.get('not_provided') }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "PropertyFeaturesTable",
        props: {
            features: {
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>