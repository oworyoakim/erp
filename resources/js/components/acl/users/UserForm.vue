<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveUser" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-3">First Name</label>
                <div class="col-sm-9">
                    <input type="text"
                           v-model="user.firstName"
                           class="form-control"
                           placeholder="Enter first name"
                           required
                    >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Last Name</label>
                <div class="col-sm-9">
                    <input type="text"
                           v-model="user.lastName"
                           class="form-control"
                           placeholder="Enter last name"
                           required
                    >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Email</label>
                <div class="col-sm-9">
                    <input type="email"
                           v-model="user.email"
                           class="form-control"
                           placeholder="Enter email address"
                           :readonly="!!user.id"
                           required
                    >
                </div>
            </div>
            <h3 class="bg-navy disabled color-palette">Login Information</h3>
            <div class="form-group row">
                <label class="col-sm-3">Role</label>
                <div class="col-sm-9">
                    <select v-model="user.roleId" class="form-control" :disabled="!!user.id" required>
                        <option value="">Select...</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Username</label>
                <div class="col-sm-9">
                    <input type="text"
                           v-model="user.username"
                           class="form-control"
                           placeholder="Username"
                           :readonly="!!user.id"
                           autocomplete="off"
                           required
                    >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Password</label>
                <div class="col-sm-9">
                    <input type="password"
                           v-model="user.password"
                           class="form-control"
                           placeholder="Enter password"
                           autocomplete="off"
                           :required="!user.id"
                    >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Confirm Password</label>
                <div class="col-sm-9">
                    <input type="password"
                           v-model="passwordConfirmation"
                           class="form-control"
                           placeholder="Confirm password"
                           :required="!user.id"
                    >
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formIsInvalid" class="btn btn-info btn-block add-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import User from "../../../models/acl/User";
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            roles(){
                return this.formSelectionOptions.roles;
            },
            title() {
                return (!!this.user.id) ? "Edit User" : "Add User";
            },
            formIsInvalid() {
                let passwordsMismatch = !!this.user.password && this.user.password !== this.passwordConfirmation;
                return this.isSending ||
                    !(!!this.user.firstName.trim() && !!this.user.lastName.trim() && !!this.user.email.trim() && !!this.user.username.trim() && !!this.user.roleId) ||
                    passwordsMismatch;
            },
        },
        created() {
            EventBus.$on("EDIT_USER", this.editUser);
        },
        data() {
            return {
                user: new User(),
                passwordConfirmation: null,
                isEditing: false,
                isSending: false,
            }
        },
        methods: {
            editUser(user = null) {
                if (!!user) {
                    this.user = user;
                } else {
                    this.user = new User();
                }
                this.isEditing = true;
            },
            async saveUser() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_USER", this.user);
                    this.isSending = false;
                    toastr.success(response);
                    this.resetForm();
                    EventBus.$emit("USER_SAVED");
                } catch (error) {
                    console.error(error);
                    this.isSending = false;
                    toastr.error(error);
                }
            },
            resetForm() {
                this.user = new User();
                this.passwordConfirmation = '';
                this.isEditing = false;
                $(".modal-backdrop").remove();
            }
        },
    }
</script>

<style scoped>

</style>
