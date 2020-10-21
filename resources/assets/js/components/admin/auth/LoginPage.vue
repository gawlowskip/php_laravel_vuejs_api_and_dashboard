<template>
    <div class="container" id="loginPage">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" id="loginCard">
                    <div class="card-header">{{ trans.get('login') }}</div>

                    <div class="card-body">

                        <form @submit.prevent="login()">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans.get('email') }}</label>

                                <div class="col-md-6">
                                    <input type="email" id="email" class="form-control" v-model="email" value autofocus
                                           required>
                                    <small class="form-text text-muted" v-if="validationError('email')">
                                        {{ validationError('email') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans.get('password') }}</label>

                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" v-model="password"
                                           value required>
                                    <small class="form-text text-muted" v-if="validationError('password')">
                                        {{ validationError('password') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" name="button">{{ trans.get('login') }}</button>
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
        name: "LoginPage",
        data() {
            return {
                email: '',
                password: '',
            }
        },
        methods: {
            login() {
                this.$store.dispatch('auth/login', {
                    email: this.email,
                    password: this.password
                }).then(() => {
                    this.$router.push({
                        name: 'UsersPage'
                    })
                    this.animateElement('#loginPage', 'bounceOutRight', {startDelay: 500, removeAnimation: false})
                }).catch(error => {
                    this.errors = error.response.data.error
                    this.errorMessage()
                    this.validationAnimation('#loginCard')
                })
            },
        }
    }
</script>

<style scoped>

</style>