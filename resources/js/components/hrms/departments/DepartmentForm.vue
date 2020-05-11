<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveDepartment">
            <div v-if="!!!scope" class="form-group row">
                <label class="col-md-4">Directorate</label>
                <div class="col-md-8">
                    <select v-model="department.directorateId" class="form-control select2"
                            :disabled="!!department.id || !!directorateId">
                        <option value="">Select directorate...</option>
                        <option v-for="directorate in directorates"
                                :value="directorate.id"
                                :key="directorate.id"
                                v-text="directorate.title">
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Department Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="department.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Description</label>
                <div class="col-md-8">
                    <textarea v-model="department.description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import {mapGetters} from "vuex";
    import Department from "../../../models/hrms/Department";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: ['directorateId', 'scope'],
        created() {
            EventBus.$on('EDIT_DEPARTMENT', this.editDepartment);
        },
        data() {
            return {
                department: new Department(),
                isSending: false,
                isEditing: false,
            };
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            directorates() {
                return this.formSelectionOptions.directorates;
            },
            title() {
                return (!!this.department.id) ? "Edit Department" : "New Department";
            },
            formInvalid() {
                return this.isSending || !(!!this.department.title);
            },
        },
        methods: {
            editDepartment(department = null) {
                if (department) {
                    this.department = deepClone(department);
                } else {
                    this.department = new Department();
                    this.department.directorateId = this.directorateId || '';
                }
                this.department.scope = this.scope;
                this.isEditing = true;
            },
            async saveDepartment() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DEPARTMENT', this.department);
                    toastr.success(response);
                    this.resetForm();
                    this.isSending = false;
                    EventBus.$emit('DEPARTMENT_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.department = new Department({
                    directorateId: this.directorateId || '',
                    scope: this.scope || ''
                });
                this.isEditing = false;
                $(".modal-backdrop").remove();
            },
        },
    }
</script>

<style scoped>

</style>
