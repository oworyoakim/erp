<template>
    <!-- Add User Modal -->
    <div ref="userModal" id="userModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Information</h5>
                    <button
                        @click="closeModal()"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveUser" onautocomplete>
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
                                       placeholder="Confirm username"
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
                </div>
            </div>
        </div>
    </div>
    <!-- /Add User Modal -->
</template>

<script>
    import User from "../../models/User";
    import {mapGetters} from "vuex";
    import {EventBus} from "../../app";

    export default {
        computed: {
            ...mapGetters({
                roles: 'ROLES',
            }),
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
                $(this.$refs.userModal).modal("show");
            },
            async saveUser() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_USER", this.user);
                    this.isSending = false;
                    toastr.success(response);
                    this.closeModal();
                    EventBus.$emit("USER_SAVED");
                } catch (error) {
                    console.error(error);
                    this.isSending = false;
                    toastr.error(error);
                }
            },
            closeModal() {
                this.user = new User();
                this.passwordConfirmation = '';
                $(this.$refs.userModal).modal("hide");
                $(".modal-backdrop").remove();
            }
        },
    }
</script>

<style scoped>

</style>
