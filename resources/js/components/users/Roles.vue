<template>
  <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
  <div v-else class="row">
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
                  <strong v-if="permission.parent_id == 0">{{ permission.name }}</strong>
                  <span v-else>____ {{ permission.name }}</span>
                </td>
                <td class="text-muted">{{permission.description}}</td>
                <td>
                  <input
                    type="checkbox"
                    @change="handleCheckbox"
                    :value="permission.slug"
                    v-model="activeRole.perms"
                    :data-parent="permission.parent_id"
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
    <div ref="roleModal" id="roleModal" class="modal custom-modal fade" role="dialog">
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
                <button :disabled="isSending" class="btn btn-info add-btn">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- /Add Role Modal -->
  </div>
</template>

<script>
import Role from "../../models/Role";
import Errors from "../../utils/Errors";

export default {
  created() {
    this.getRoles();
  },
  data() {
    return {
      activeRole: null,
      role: new Role(),
      roles: [],
      permissions: [],
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
        let response = await this.$http.get("/roles/all-json");
        this.roles = response.data.roles;
        this.permissions = response.data.permissions;
        console.log(this.roles);
        this.isLoading = false;
      } catch (error) {
        console.log(error.response.data);
        toastr.error(error.response.data);
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
          let response = await this.$http.delete("/roles/delete?role_id=" + id);
          toastr.success(response.data);
          this.getRoles();
        }
      } catch (error) {
        console.log(error.response.data);
        toastr.error(error.response.data);
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
        if (!!this.role.id) {
          // update
          let response = await this.$http.put("/roles", {
            id: this.role.id,
            name: this.role.name,
            description: this.role.description
          });
          toastr.success(response.data);
        } else {
          // insert new
          let response = await this.$http.post("/roles", {
            name: this.role.name,
            description: this.role.description
          });
          toastr.success(response.data);
        }
        this.isSending = false;
        this.getRoles();
        this.closePreview();
      } catch (error) {
        console.log(error.response.data);
        toastr.error(error.response.data);
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
            if (!this.activeRole.perms.includes(value)) {
              this.activeRole.perms.push(value);
            }
          } else {
            let permissions = this.activeRole.perms.filter(permission => {
              return permission != value;
            });

            this.activeRole.perms = permissions;
          }
        });
      }
    },
    async updatePermissions() {
      try {
        // update
        let response = await this.$http.patch("/roles", {
          role_id: this.activeRole.id,
          permissions: this.activeRole.perms
        });
        toastr.success(response.data);
        this.getRoles();
      } catch (error) {
        console.log(error.response.data);
        toastr.error(error.response.data);
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
