<template>
    <div>
        <label :for="id" v-if="label">{{ label }}</label>
        <multiselect track-by="value" label="name" :placeholder="placeholderString" :options="options" :searchable="searchable" :multiple="multiple" @input="updateValue" :value="value">
            <template :slot="`select_${id}`" slot-scope="{ option }">{{ option.name }}</template>
        </multiselect>
        <small class="form-text text-muted" v-if="validationError(id)">
            {{ validationError(id) }}
        </small>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.min.css'

    export default {
        name: "BaseSelect",
        components: {
            Multiselect
        },
        props: {
            id: {
                type: String,
                default: ''
            },
            label: {
                type: String,
                default: ''
            },
            options: {
                required: true
            },
            placeholder: {
                type: String,
                default: ''
            },
            multiple: {
                type: Boolean,
                default: false
            },
            searchable: {
                type: Boolean,
                default: false
            },
            errorsData: {
                required: true
            },
            value: [String, Number, Object, Array]
        },
        watch: {
            errorsData(errors) {
                this.errors = errors
            }
        },
        data() {
            return {
                placeholderString: ''
            }
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value)
            }
        },
        created() {
            this.placeholderString = this.placeholder ? this.placeholder : this.trans.get('choose_option')
        },
    }
</script>

<style scoped>

</style>