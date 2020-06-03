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
                <a href="javascript:void(0)" class="btn btn-primary btn-block" @click="editRole()">
                    <i class="fa fa-plus"></i> Add Role
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
                                Type:
                                <div v-if="activeRole.type === 'system'" class="status-toggle">System User Only</div>
                                <div v-else-if="activeRole.type === 'employee'" class="status-toggle">Employee Only</div>
                                <div v-else class="status-toggle">Both Employee and System User</div>
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
            <app-role-form/>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            title: String,
        },
        created() {
            this.getRoles();
            EventBus.$on(['ROLE_SAVED', 'PERMISSIONS_UPDATED','ROLE_DELETED'], this.getRoles);
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
                isLoading: true,
            };
        },
        methods: {
            async getRoles() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_ROLES");
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
                        EventBus.$emit('ROLE_DELETED');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editRole(role = null) {
                EventBus.$emit('EDIT_ROLE', role);
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
                    EventBus.$emit('PERMISSIONS_UPDATED');
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    };
</script>
