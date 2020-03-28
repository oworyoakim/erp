<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row m-b-5">
            <div class="col-sm-8">
                <h2>{{title}}</h2>
            </div>
            <div class="col-sm-4">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#roleModal">
                    <i class="fa fa-plus"></i> Add Roles
                </a>
                <div class="roles-menu">
                    <ul>
                        <li
                            v-for="rl in roles"
                            v-bind:class="{active: !!activeRole && rl.id === activeRole.id}"
                            :key="rl.id"
                        >
                            <a href="javascript:void(0);" @click="activeRole = rl">{{rl.name}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9 table-responsive">
                <template v-if="!!activeRole">
                    <div class="m-b-30">
                        <ul class="list-group notification-list">
                            <li class="list-group-item">
                                Role Name:
                                <div class="status-toggle">{{activeRole.name}}</div>
                            </li>
                            <li class="list-group-item">
                                Description:
                                <div class="status-toggle">{{activeRole.description}}</div>
                            </li>
                            <li class="list-group-item">
                                Action:
                                <div class="status-toggle">
                                    <a @click="editRole(activeRole)" class="btn btn-outline-info btn-sm" href="#">
                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                    </a>
                                    <a
                                        @click="deleteRole(activeRole.id)"
                                        class="btn btn-outline-danger btn-sm"
                                        href="#"
                                    >
                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="m-b-5">
                        <h3>Role Permissions</h3>
                        <form @submit.prevent="updatePermissions">
                            <table class="table table-striped custom-table">
                                <tr v-for="permission in permissions" :key="permission.id">
                                    <td>
                                        <strong v-if="permission.parentId === 0">{{ permission.name }}</strong>
                                        <span v-else>____ {{ permission.name }}</span>
                                    </td>
                                    <td class="text-muted">{{permission.description}}</td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            @change="handleCheckbox"
                                            :value="permission.slug"
                                            v-model="activeRole.permissions"
                                            :data-parent="permission.parentId"
                                            :id="permission.id"
                                        />
                                    </td>
                                </tr>
                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <button class="btn add-btn btn-sm">Update</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </template>
            </div>
            <!-- Add Role Modal -->
            <div ref="roleModal" id="roleModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
                 data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Role Information</h5>
                            <button
                                @click="closePreview()"
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveRole">
                                <div class="form-group row">
                                    <label class="col-sm-4">
                                        Role Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input
                                            v-model="role.name"
                                            v-bind:class="{'is-invalid': !!errors.name}"
                                            class="form-control"
                                            type="text"
                                        />
                                        <span v-if="!!errors.name" class="invalid-feedback" v-text="errors.name"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Description</label>
                                    <div class="col-sm-8">
                                        <textarea v-model="role.description" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button :disabled="isSending" class="btn btn-info btn-block add-btn">
                                        <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                                        <span v-else>Save</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Role Modal -->
        </div>
    </div>
</template>

<script>
    import Role from "../../models/Role";
    import Errors from "../../utils/Errors";
    import {mapGetters} from "vuex";

    export default {
        props: {
            title: String,
        },
        created() {
            this.getRoles();
        },
        computed: {
            ...mapGetters({
                roles: 'ROLES',
                permissions: 'PERMISSIONS',
            }),
        },
        data() {
            return {
                activeRole: null,
                role: new Role(),
                errors: new Errors({
                    name: "",
                    description: ""
                }),
                isLoading: true,
                isSending: false
            };
        },
        methods: {
            async getRoles() {
                try {
                    this.isLoading = true;
                    let response = await this.$store.dispatch("GET_ROLES");
                    console.log(this.roles, this.permissions);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            async deleteRole(id) {
                try {
                    let isConfirm = await swal({
                        title: "Are you sure?",
                        text: "You will delete this record!",
                        icon: "warning",
                        buttons: ["No", "Yes"],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if (isConfirm) {
                        let response = await this.$store.dispatch("DELETE_ROLE", id);
                        this.activeRole = null;
                        toastr.success(response);
                        this.getRoles();
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editRole(role) {
                this.role = JSON.parse(JSON.stringify(role));
                console.log(this.role);
                $(this.$refs.roleModal).modal("show");
            },
            async saveRole() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_ROLE", {
                        id: this.role.id,
                        name: this.role.name,
                        description: this.role.description
                    });
                    toastr.success(response);
                    this.isSending = false;
                    this.getRoles();
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isSending = false;
                    this.closePreview();
                }
            },
            validateRoleInfo() {
                if (!!!this.role.name) {
                    this.errors.set("name", "Role name is required");
                } else if (String(this.role.name).trim().length < 3) {
                    this.errors.set(
                        "name",
                        "Role name must have 3 or more characters long"
                    );
                } else if (this.errors.name) {
                    this.errors.clear("name");
                }

                return this.errors.has("name");
            },

            handleCheckbox(event) {
                let parentId = event.target.getAttribute("data-parent");
                if (parentId == 0) {
                    const id = event.target.getAttribute("id");
                    const checked = !!event.target.checked;
                    const children = $(":checkbox[data-parent=" + id + "]");
                    children.each(index => {
                        let child = children[index];
                        let value = child.getAttribute("value");
                        //console.log(value);
                        if (!!checked) {
                            if (!this.activeRole.permissions.includes(value)) {
                                this.activeRole.permissions.push(value);
                            }
                        } else {
                            let permissions = this.activeRole.permissions.filter(permission => {
                                return permission != value;
                            });

                            this.activeRole.permissions = permissions;
                        }
                    });
                }
            },
            async updatePermissions() {
                try {
                    // update
                    let response = await this.$store.dispatch("UPDATE_PERMISSIONS", {
                        role_id: this.activeRole.id,
                        permissions: this.activeRole.permissions
                    });
                    toastr.success(response);
                    this.getRoles();
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            closePreview() {
                this.role = new Role();
                $(this.$refs.roleModal).modal("hide");
                $(".modal-backdrop").remove();
            }
        }
    };
</script>
