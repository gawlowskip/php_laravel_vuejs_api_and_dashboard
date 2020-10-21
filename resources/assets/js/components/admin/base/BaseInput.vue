<template>
    <div>
        <label :for="id" v-if="label">{{ label }}</label>
        <input :id="id" :name="id" :type="type" class="form-control" :aria-describedby="placeholderString"
               :placeholder="placeholderString" :value="value" @input="updateValue">
        <small class="form-text text-muted" v-if="validationError(id)">
            {{ validationError(id) }}
        </small>
    </div>
</template>

<script>
    export default {
        name: "BaseInput",
        props: {
            id: {
                type: String,
                default: ''
            },
            label: {
                type: String,
                default: ''
            },
            type: {
                type: String,
                default: 'text'
            },
            placeholder: {
                type: String,
                default: ''
            },
            errorsData: {
                required: true
            },
            value: [String, Number]
        },
        data() {
            return {
                placeholderString: ''
            }
        },
        watch: {
            errorsData(errors) {
                this.errors = errors
            },
            label(label) {
                this.label = label
                this.placeholderString = this.placeholder ? this.placeholder : this.label
            }
        },
        methods: {
            updateValue(event) {
                this.$emit('input', event.target.value)
            }
        },
        created() {
            this.placeholderString = this.placeholder ? this.placeholder : this.label
        },
    }
</script>

<style scoped>

</style>