<template>
    <div>
        <label :for="id" v-if="label">{{ label }}</label>
        <input :id="id" :name="id" type="file" ref="file" class="form-control" @input="updateValue"
               :multiple="multiple" :accept="accept" :value="valueString">
        <small class="form-text text-muted" v-if="validationError(id)">
            {{ validationError(id) }}
        </small>
    </div>
</template>

<script>
    export default {
        name: "BaseInputFile",
        props: {
            id: {
                type: String,
                default: ''
            },
            label: {
                type: String,
                default: ''
            },
            accept: {
                type: String,
                required: true
            },
            errorsData: {
                required: true
            },
            multiple: {
                required: false,
                default: false
            },
            value: [File, FileList]
        },
        watch: {
            errorsData(errors) {
                this.errors = errors
            }
        },
        data() {
            return {
                valueString: ''
            }
        },
        methods: {
            updateValue(event) {
                let value = this.$refs.file.files[0]

                if (!value) {
                    value = null
                }

                if (this.multiple) {
                    value = this.$refs.file.files
                }

                this.$emit('input', value)
            }
        },
    }
</script>

<style scoped>

</style>