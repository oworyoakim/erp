<template>
    <div class="account-box">
        <div class="account-wrapper" v-if="$store.state.loginPageForm === 'login'">
            <h3 class="account-title">Login</h3>
            <p class="account-subtitle">Start your session</p>
            <div v-if="$store.state.error" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$store.state.error}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" class="fa fa-times"></span>
                </button>
            </div>
            <div v-if="$store.state.success" class="alert alert-success alert-dismissible fade show" role="alert">
                {{$store.state.success}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" class="fa fa-times"></span>
                </button>
            </div>
            <!-- Account Form -->
            <form @submit.prevent="login">
                <div class="form-group">
                    <label>Login Name</label>
                    <input v-model="loginForm.login_name" type="text" class="form-control"
                           placeholder="Enter login name" title="Please enter your login name" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input v-model="loginForm.password" type="password" class="form-control" placeholder="******"
                           title="Please enter your password" required>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit">Login</button>
                </div>
                <div class="account-footer">
                    <p>
                        <a href="javascript:void(0);" @click="goToResetForm()">Reset Password?</a>
                    </p>
                </div>
            </form>
            <!-- /Account Form -->
        </div>
        <div class="account-wrapper" v-if="$store.state.loginPageForm === 'reset'">
            <h3 class="account-title">Reset your password</h3>
            <br/>
            <!-- Password Reset Form -->
            <form @submit.prevent="resetPassword">
                <div class="form-group">
                    <label>Email Address</label>
                    <input v-model="loginForm.email" type="email" class="form-control"
                           placeholder="Enter your email address" title="Please enter your email address" required>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit">Reset Password</button>
                </div>
                <div class="account-footer">
                    <p>
                        <a href="javascript:void(0);" @click="backToLoginForm()">Back to Login?</a>
                    </p>
                </div>
            </form>
            <!-- /Password Reset Form -->
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loginForm: {
                    login_name: '',
                    password: '',
                    email: '',
                }
            }
        },
        methods: {
            login() {
                this.$store.dispatch('LOGIN', this.loginForm)
                    .then((success) => {
                        toastr.success(success);
                        this.$store.state.success = success;
                        location.reload();
                    })
                    .catch((error) => {
                        this.$store.state.error = error;
                    });
            },
            goToResetForm() {
                this.$store.state.loginPageForm = 'reset';
            },
            backToLoginForm() {
                this.$store.state.loginPageForm = 'login';
            },
            resetPassword() {
                this.$store.dispatch('RESET_PASSWORD', this.loginForm)
                    .then((success) => {
                        toastr.success(success);
                        this.$store.state.success = success;
                        this.backToLoginForm();
                    })
                    .catch((error) => {
                        this.$store.state.error = error;
                    });
            }
        }
    }
</script>

<style scoped>

</style>
