<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ trans.get('register') }}</div>

                    <div class="card-body">
                        <form @submit.prevent="register()">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ trans.get('first_name') }}</label>

                                <div class="col-md-6">
                                    <input type="text" id="name" class="form-control" value v-model="name">
                                    <small class="form-text text-muted" v-if="validationError('name')">
                                        {{ validationError('name') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ trans.get('last_name') }}</label>

                                <div class="col-md-6">
                                    <input type="text" id="last_name" class="form-control" value v-model="last_name">
                                    <small class="form-text text-muted" v-if="validationError('last_name')">
                                        {{ validationError('last_name') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans.get('email') }}</label>

                                <div class="col-md-6">
                                    <input type="email" id="email" class="form-control" v-model="email" value
                                           required>
                                    <small class="form-text text-muted" v-if="validationError(errors, 'email')">
                                        {{ validationError('email') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans.get('password') }}</label>

                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" v-model="password"
                                           value required>
                                    <small class="form-text text-muted" v-if="validationError(errors, 'password')">
                                        {{ validationError('password') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" name="button">{{ trans.get('register') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "RegisterPage",
        data() {
            return {
                name: '',
                last_name: '',
                email: '',
                password: '',
            }
        },
        methods: {
            register() {
                this.$store.dispatch('auth/register', {
                    name: this.name,
                    last_name: this.last_name,
                    email: this.email,
                    password: this.password
                }).then(() => {
                    this.$router.push({
                        name: 'UsersPage'
                    })
                }).catch(error => {
                    this.errors = error.response.data.error;
                    this.errorMessage()
                })
            }
        }
    }
</script>

<style scoped>

</style>