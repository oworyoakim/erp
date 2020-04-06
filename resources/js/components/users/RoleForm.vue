<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveRole" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">
                    Role Name
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <input
                        v-model="role.name"
                        class="form-control"
                        type="text"
                        required
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <textarea v-model="role.description" class="form-control"></textarea>
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-info btn-block add-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import Role from "../../models/Role";
    import {deepClone} from "../../utils/helpers";
    import {EventBus} from "../../app";

    export default {
        created() {
            EventBus.$on(['EDIT_ROLE'], this.editRole);
        },
        computed: {
            title() {
                return (!!this.role.id) ? "Edit Role" : "Add Role";
            },
            formInvalid() {
                return this.isSending || !(!!this.role.title && this.role.name.length >= 3);
            }
        },
        data() {
            return {
                role: new Role(),
                isEditing: false,
                isSending: false
            };
        },
        methods: {
            editRole(role = null) {
                if (role) {
                    this.role = deepClone(role);
                } else {
                    this.role = new Role();
                }
                this.isEditing = true;
            },
            async saveRole() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_ROLE", this.role);
                    toastr.success(response);
                    this.isSending = false;
                    this.resetForm();
                    EventBus.$emit('ROLE_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.role = new Role();
                this.isEditing = false;
            }
        }
    }
</script>

<style scoped>

</style>
